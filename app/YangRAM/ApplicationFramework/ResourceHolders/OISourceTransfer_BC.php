<?php
namespace AF\ResourceHolders;

use Status;
use Tangram\ClassLoader;
use Request;
use Application;

abstract class OISourceTransfer_BC extends ContentProvider_BC {
	public $srcresponser = 'OISourceFilesResponser';

    final public function __construct(Application $app, Request $request){
		$classname = $this->getClassName($request);
		$filename = $app->Path.'Controllers/'.$classname;
		$classname = $app->NAMESPACE.'Controllers\\'.$classname;
		$methodname = $this->getMethodName($request);
		$arguments = $this->getParameters($request);
		//die($filename);
		ClassLoader::execute($filename);
		$class = new $classname($app, $request);
		call_user_func_array(array($class, $methodname), $arguments);
	}

	final public function getClassName(Request $request){
		if(isset($request->uri_path[3])){
			$classalias = $request->uri_path[3];
			if($classalias==='resources'){
				$this->classalias = 'resources';
				return $this->srcresponser;
			}
			if(isset($this->controllers[$classalias])&&isset($this->controllers[$classalias]['classname'])){
				$this->classalias = $classalias;
				return $this->controllers[$classalias]['classname'];
			}
			return $this->prtException(1, $classalias);
		}
		$this->classalias = 'default';
		return isset($this->controllers['default'])&&isset($this->controllers['default']['classname']) ? $this->controllers['default']['classname'] : 'OI\DefaultPage';
	}

	final public function getMethodName(Request $request){
		if($this->classalias==='resources'){
			$this->methodoptions = ['minArgsLength' =>  0];
			if(isset($request->uri_path[4])){
				$methodname = $request->uri_path[4];
				if(in_array($methodname, ['ss', 'splashscreen'])){
                	return 'returnSplashScreen';
            	}
				if(in_array($methodname, ['oiss', 'oistylesheets'])){
                	return 'returnStyleSheets';
            	}
				if($methodname=='clear'){
					return 'clear';
				}
			}
            $methodname = 'os';
        	return 'returnMainOS';
		}
        if(isset($request->uri_path[4])){
			$methodname = str_replace('-', '_', strtolower($request->uri_path[4]));   
        }else{
            $methodname = 'main';
        }
        $methods = $this->controllers[$this->classalias]['methods'];
        if(isset($methods[$methodname])){
			$this->methodname = $methodname;
			$this->methodoptions = $methods[$methodname];
			if(empty($methods[$methodname]['methodname'])){
				return $methodname;
			}
			return $methods[$methodname]['methodname'];
		}
		return $this->prtException(2, $methodname);
	}

    public function getParameters(Request $request){
		$args = array_slice($request->uri_path, 5);
		if(count($args)>=$this->methodoptions['minArgsLength']){
			return $args;
		}
		return $this->prtException(3);
	}

    protected function prtException($code, $arg = ''){
		$status = new Status("Unknow class or method [ #$code $arg]");
        return $status->cast(Status::CAST_XML);
	}
}

include(PATH_TNI.'CACHE/UserFiles.php');
include(PATH_TNI.'NIDO/UserAccount.php');
include(PATH_NIAF.'Models/Certificates/StdPassport.php');