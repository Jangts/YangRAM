<?php
namespace Tangram\R5;

use Status;
use Tangram\ClassLoader;
use Tangram\APP\ApplicationPermissions;
use Tangram\APP\Application;
use SESS;

final class ResourceIndexer extends NI_ResourceIndexer_BaseClass {
    protected static function init(){
        define('UOI_DIR',   	I4S_DIR.'OperatorInterface/UOI/');
        define('UOI_PID',   	PID.UOI_DIR);
        define('PATH_UOI',      ROOT.UOI_DIR);
        ClassLoader::setNSMap([
			'OIC'	=>	PATH_NIAF.'Controllers/OIF/'				
		]);
    }
    protected $interfaceType = [
        '1' =>  'G',
        '2' =>  'S',
        '3' =>  'RT',
        '4' =>  'B',
        '5' =>  'UT',
        '11' =>  'X/OTD',
        '12' =>  'X/OTW'
    ];

    protected function checkExtentdedInterface($uri, $path, $request, $HOST){
        $uri = HOST. $uri . '/';
		$_DOI_DIR_ = '/_deuoi_'.hash('sha256', _DOI_TOKEN_).'_/';
		if(stripos($uri.'/', $HOST.$_DOI_DIR_)===0){
            // Uniform Operator Interface
            $request->update();
            //define('__GET_DIR', __DIR._GETTER_);
            define('__UOI_DIR', __DIR.$_DOI_DIR_);
            return [
                'map'       =>  (isset($path[2])&&$path[2]!=='') ? -11 : -4,
                'app'       =>  (isset($path[2])&&$path[2]!=='') ? $path[2] : 'UOI'
            ];
		}
        if(_WEBUOI_ENABLE_){
    		if(stripos($uri.'/', $HOST._WEBUOI_)===0){
                // 'Uniform Operator Interface'
                $request->update();
                define('__UOI_DIR', __DIR._WEBUOI_);
                return [
                    'map'       =>  (isset($path[2])&&$path[2]!=='') ? -12 : -4,
                    'app'       =>  (isset($path[2])&&$path[2]!=='') ? $path[2] : 'UOI'
                ];
    		}
        }
        return ['map' => 0];
    }

    protected function routeExtendinterfaces(Application $app, ApplicationPermissions $permissions){
        switch (RT_CURR) {
            case 'X/OTD':
                $params = Request::instance()->PARAMS;
                if($params->session_id){
                    SESS::init($params->session_id);
                }
                
            case 'X/OTW':
                $this->checkTasker(11, 1, '1');
                include(PATH_NIAF.'ResourceHolders/OISourceTransfer_BaseClass.php');
                return $app->get([], 'OISourceTransfer');
        }
        return new Status(404, '', 'This Application Has No Requested Resource Holder!!', true);
    }
}
