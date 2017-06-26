<?php
namespace Tangram\R5;

use Status;
use Tangram\AsyncTask;
use Tangram\NIDO\DataObject;
use Tangram\NIDO\Resource;
use Tangram\APP\ApplicationPermissions;
use Tangram\APP\Application;

/**
 *	Uniform Resource Indexer
 *	统一资源索引器————又名统一路由索引器
 *  单例类，并未限制调用者，只是恰好由运行时（$RUNTIME，主控制器实例）抢先实例化
 *	负责解析请求，获得子应用ID和路由代号，并为进程提供一个请求读取器（Tangram\R5\Request）实例
 */
abstract class NI_ResourceIndexer_BC {
    protected static $instance = NULL, $res = NULL;

    protected static function init(){
        die('Must Have "init" Method');
    }

	public static function instance(){
		if(self::$instance === NULL){
            static::init();
			self::$instance = new static();
		}
		return self::$instance;
	}

    /* 委托 */
    public static function delegate($data){
		return self::$res->delegate($data);
	}

    protected $interfaceType = [
        '1' =>  'G',
        '2' =>  'S',
        '3' =>  'RT',
        '4' =>  'B',
        '5' =>  'UT'
    ];

    public $ROUTE, $COLUMN;

    protected function __construct(){
        $request = Request::instance();
		$uri = $request->TRANSLATED_URI;
        $path = $request->uri_path;
        $HOST = $request->HOST;
        self::$res = Resource::instance($request)->render();
        $temporary = $this->checkStandardInterface($uri, $path, $request, $HOST);
        if($temporary['map']>=0){
            $temporary = $this->checkExtentdedInterface($uri, $path, $request, $HOST);
        }
        if($temporary['map']>=0){
            $temporary = $this->checkRESTfulAPI($uri, $path, $request, $HOST);
        }

        while ($temporary['map']>=0) {
            // Custom Router Interface
            $temporary = $this->resolverCustomInterface($temporary['map'], $uri, $path, $request);
        }

        $type = $this->checkType(abs($temporary['map']), $uri);
        
        setcookie('language', REQUEST_LANGUAGE, time()+315360000, '/', HOST, _USE_HTTPS_, true);
        if(isset($temporary['app'])){
            define('AC_CURR', $temporary['app']);
            define('RT_CURR', $type);
            define('__GET_DIR', __DIR._GETTER_);
            define('__SET_DIR', __DIR._SETTER_);
        }else{
            new Status(404, true);//Status::notFound();
        }
    }

    private function checkType($code, $uri){
        if(isset($this->interfaceType[$code])){
            return $this->interfaceType[$code];
        }

        if($code==404) {
            if(str_replace('/', '', $uri)){
                $status = new Status(404, '', $_SERVER['REQUEST_URI']);
                return $status->cast(Status::CAST_PAGE, Status::TEMP_404);
            }
            $status = new Status(200);
            return $status->cast(Status::CAST_PAGE);
        }
        $status = new Status(703.3, 'Unknow Routing Return Code');
        return $status->cast(Status::CAST_PLOG);
    }

    private function checkStandardInterface($uri, $path, $request, $HOST){
        $uri = strtolower(HOST). $uri . '/';
        if(\Tangram\IDEA::MODE==='DEVELOP'){
            define('_TESTER_', '/unit/');
            if(stripos($uri, HOST._TESTER_)===0){
                // Unit Tester Interface
                define('_TEST_MODE_', true);
                
                $request->update(NULL, NULL, false);
                return [
                    'map'       =>  -5,
                    'app'       =>  (isset($path[2])&&$path[2]!=='') ? $path[2] : 'yangram'
                ];
    		}
        }
        define('_TEST_MODE_', false);
		if(stripos($uri, $HOST._GETTER_)===0){
            // Uniform Request Interface
            $request->update();
            return [
                'map'       =>  -1,
                'app'       =>  (isset($path[2])&&$path[2]!=='') ? $path[2] : NULL
            ];
		}
		if(stripos($uri, $HOST._SETTER_)===0){
            // Unified Proposal Interface
            $appid = (isset($path[2])&&$path[2]!=='') ? $path[2] : NULL;
            $request->update();
            return [
                'map'       =>  -2,
                'app'       =>  $appid
            ];
		}
        return ['map' => 0];
    }

    protected function checkExtentdedInterface($uri, $path, $request, $HOST){
        return ['map' => 0];
    }

    public function checkRESTfulAPI($uri, $path, $request){
        $uri = strtolower(HOST). $uri . '/';
        $data = RouteMapper::getDirnames();
        foreach($data as $dir=>$item){
		    if(stripos($uri, $dir)===0){
                define('__METHOD', strtolower($_SERVER['REQUEST_METHOD']));
                define('__REST_PARAM_INDEX', $item['LENGTH']);
                $request->update();
                $this->COLUMN = $request->checkColumn()->COLUMN;
                return [
                    'map'       =>  -3,
                    'app'       =>  $item['HDL_ID']
                ];
            }
		}
        return ['map' => 0];
    }

    public function resolverCustomInterface($mapid, $uri, $path, $request){
        $uri = strtolower(HOST). $uri;
        $data = RouteMapper::getPatterns($mapid);
        foreach($data as $item){
            $PATTERN = $item['PATTERN'];
            if(preg_match($PATTERN, $uri, $matches)){
                if($item['HDL_TYPE']==='APP'){
                    $request->update($item, $matches);
                    $GLOBALS['ROUTE'] = $this->ROUTE = DataObject::enclose($item);
                    $this->COLUMN = $request->checkColumn()->COLUMN;
                    unset($request);
                    return [
                        'map'       =>  -4,
                        'app'       =>  $item['HDL_ID']
                    ];
                }elseif($item['HDL_TYPE']==='MAP'){
                    return ['map' => $item['HDL_ID']];
                }else{
                    return [
                        'map'       => -702,
                    ];
                }
            }
        }
        return [
            'map'       => -404,
        ];
    }

    public function point(Application $app, ApplicationPermissions $permissions){
        $resHolders = $app->Props['ResHolders'];

        switch (RT_CURR) {
            case 'G':
                return $app->get([], 'ContentProvider');

            case 'RT':
                if($resHolders['ResourceTransfer']) return $app->set([], 'ResourceTransfer');

                $status = new Status(404, '', 'This Application Has No Resource Representational State Transfer!!');
                return $status->cast(Status::CAST_PAGE);

            case 'B':
                $this->checkTasker(17, 2,'11');

                if($resHolders['ResourceBrowser']) return $app->get([], 'ResourceBrowser');

                $status = new Status(404, '', 'This Application Has No Custom Routing Resource Browser!!');
                return $status->cast(Status::CAST_PAGE);

            case 'S':
                $this->checkTasker( 17, 1, '1');
                
                if($resHolders['ResourceSetter']) return $app->set([], 'ResourceSetter');
                
                return new Status(404, '', 'This Application Has No Resource Setter!!', true);

            case 'UT':
                if(_TASKER_ENABLE_) $this->checkTasker();

                if($resHolders['UnitTester']&&is_file($filename = $app->Path.$resHolders['UnitTester']['filename'])){
                    include($filename);
                    $app->test();
                }
                Response::instance()->send('YangRAM Unit Test: ' . sprintf('Use Time: %.2f ms, Use Memory: %.2f Mb', microtime(true)*1000 - BOOTTIME, memory_get_peak_usage() / 1048576));

            default:
            $this->routeExtendinterfaces($app, $permissions);
        }
    }

    protected function routeExtendinterfaces(Application $app, ApplicationPermissions $permissions){
        return new Status(404, '', 'This Application Has No Requested Resource Holder!!', true);
    }

    protected function checkTasker($start, $length, $value){
        if(_TASKER_ENABLE_&&substr(DATETIME, $start, $length)==$value) {
            include(PATH_SYS.'AsyncTask.php');
            AsyncTask::checkMasker();
        }
    }
}
