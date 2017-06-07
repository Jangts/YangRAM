<?php
namespace AF\ResourceHolders;

use Status;
use System\ClassLoader;
use Request;
use Application;

abstract class ResourceTransfer_BaseClass extends \System\R5\NI_ResourceHolder_BaseClass {
    final public function __construct(Application $app, Request $request){	
		$classname = $this->getClassName($request);
		if($classname){
			$filename = $app->Path.'Controllers/'.$classname;
			$classname = $app->NAMESPACE.'Controllers\\'.$classname;
			$methodname = $this->getMethodName($request);
			$arguments = $request->REST_PARAMS;
			ClassLoader::execute($filename);
			if(class_exists($classname)){
				$class = new $classname($app, $request);
            	if(method_exists($class, $methodname)){
                	call_user_func_array([$class, $methodname], $arguments);
            	}else{
                	new Status(404, 'Unsupported Method', "Method $classname::$methodname Not Found! Files of application [$app->Name] on your i4s(YANGRAM INTERACTIVE INFORMATION INTELLIGENT INTEGRATION SYSTEM) may have been tampered.", true);
            	}
        	}else{
            	new Status(404, 'Resource Not Found', "Class $classname Not Found! Files of application [$app->Name] on your i4s(YANGRAM INTERACTIVE INFORMATION INTELLIGENT INTEGRATION SYSTEM) may have been tampered.", true);
        	}
		}else{
			$this->reutrnAPIList();
		}
	}

	protected function getClassName(Request $request){
		if(defined('__REST_PARAM_INDEX')){
			if(isset($request->URI_PATH[__REST_PARAM_INDEX])){
				$resources = strtolower($request->URI_PATH[__REST_PARAM_INDEX]);
				if(isset($this->controllers[$resources])){
					return $this->controllers[$resources];
				}
			}
        }
		return false;
	}

	protected function getMethodName(Request $request){
		return __METHOD;
	}

	protected function reutrnAPIList(){

	}
}