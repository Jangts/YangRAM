<?php
namespace AF\ResourceHolders\traits;

use Status;
use System\ClassLoader;
use Request;
use AF\Models\Certificates\Passport;
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
                call_user_func_array([$class, $methodname], $arguments);
            }else{
                new Status(705.3, 'Contronller Method Not Found', "Method $classname::$methodname Not Found! Files of application [$app->Name] on your i4s(YANGRAM INTERACTIVE INFORMATION INTELLIGENT INTEGRATION SYSTEM) may have been tampered.", true);
            }
        }else{
            new Status(705.2, 'Contronller Not Found', "Class $classname Not Found! Files of application [$app->Name] on your i4s(YANGRAM INTERACTIVE INFORMATION INTELLIGENT INTEGRATION SYSTEM) may have been tampered.", true);
        }
	}

	protected function getClassName(Request $request){
		if($request->PARAMS->c){
			$classalias = strtolower($request->PARAMS->c);
			if(isset($this->controllers[$classalias])){
                $this->classalias = $classalias;
				return $this->controllers[$this->classalias]['classname'];
			}
			new Status(700.2, 'Class Specified Error', 'Undeclared Classalias [' . $classalias . ']', true);
		}
		if(isset($request->uri_path[3])){
			$classalias = $request->uri_path[3];
			if(isset($this->controllers[$classalias])&&isset($this->controllers[$classalias]['classname'])){
				$this->classalias = $classalias;
				return $this->controllers[$classalias]['classname'];
			}
            new Status(700.2, 'Class Specified Error', 'Undeclared Classalias [' . $classalias . ']', true);
		}
		new Status(700.1, 'Classname Unspecified', true);
	}

	protected function getMethodName(Request $request){
		$methods = $this->controllers[$this->classalias]['methods'];
		$methodname = str_replace('-', '_', $request->PARAMS->m);
		if(!$methodname){
			if(isset($request->uri_path[4])){
				$methodname = str_replace('-', '_', $request->uri_path[4]);
			}else{
				$methodname = 'main';
			}
		};
		$methodname = strtolower($methodname);
        if(isset($methods[$methodname])){
			$this->methodname = $methodname;
			$this->methodoptions = $methods[$methodname];
			if(empty($methods[$methodname]['methodname'])){
				return $methodname;
			}
			return $methods[$methodname]['methodname'];
		}
		new Status(700.3, 'Methodname Specified Error', 'Undeclared Methodname [' . $methodname . '] For Class ' . $this->controllers[$this->classalias]['classname'], true);
	}

	protected function getParameters(Request $request){
		if(is_string($request->FORM->args)){
			$args = explode('/', $request->FORM->args);
		}elseif(is_string($request->PARAMS->a)){
			$args = explode('/', $request->PARAMS->a);
		}else{
			$args = array_slice($request->uri_path, 5);
		}
		if(count($args)>=$this->methodoptions['minArgsLength']){
			return $args;
		}
        $methods = $this->controllers[$this->classalias]['methods'];
        $methodname = $this->methodname;
        if(isset($methods[$methodname]['methodname'])){
            $methodname = $methods[$this->methodname]['methodname'];
        }
		new Status(700.4, 'Parameters Given Error', 'Insufficient Number Of Parameters For [' . $this->controllers[$this->classalias]['classname'] . '::' . $methodname . '], this method needs at least '.$this->methodoptions['minArgsLength'].' arguments, given is '.count($args).'.', true);
	}
}
