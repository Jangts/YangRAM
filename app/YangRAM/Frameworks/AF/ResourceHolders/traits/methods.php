<?php
namespace AF\ResourceHolders\traits;

use Status;
use Tangram\ClassLoader;
use Request;
use AF\Models\Certificates\StdPassport;
use Application;

trait methods {
    public function __construct(Application $app, Request $request){
		$classalias = $this->getClassName($request);
		$classname = '\\'.$app->Props['Namespace'].'\\Controllers\\'.$classalias;
		$methodname = $this->getMethodName($request);
		$arguments = $this->getParameters($request);
        $filename = $app->Path.'Controllers/'.$classalias;
		ClassLoader::execute($filename);
        if(class_exists($classname)){
			$class = new $classname($app, $request);
            if(method_exists($class, $methodname)){
				try{
					call_user_func_array([$class, $methodname], $arguments);
				} catch(Exception $e) {
					new Status(705.3, 'Parameters Error', "Error Parameters Be Given to Method $classname::$methodname!", true);
				};
            }else{
                new Status(705.3, 'Contronller Method Not Found', "Method $classname::$methodname Not Found!", true);
            }
        }else{
            new Status(705.2, 'Contronller Not Found', "Class $classname Not Found!", true);
        }
	}

	protected function getClassName(Request $request){
		if($request->PARAMS->c){
			return strtolower($request->PARAMS->c);
		}
		if(isset($request->uri_path[3])){
			return $request->uri_path[3];
		}
		new Status(700.1, 'Classname Unspecified', true);
	}

	protected function getMethodName(Request $request){
		$methodname = str_replace('-', '_', $request->PARAMS->m);
		if(!$methodname){
			if(isset($request->uri_path[4])){
				$methodname = str_replace('-', '_', $request->uri_path[4]);
			}else{
				$methodname = 'main';
			}
		};
		return strtolower($methodname);
	}

	protected function getParameters(Request $request){
		if(is_string($request->FORM->args)){
			$args = explode('/', $request->FORM->args);
		}elseif(is_string($request->PARAMS->a)){
			$args = explode('/', $request->PARAMS->a);
		}else{
			$args = array_slice($request->uri_path, 5);
		}
		return $args;
	}
}
