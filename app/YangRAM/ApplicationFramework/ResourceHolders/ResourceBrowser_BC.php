<?php
namespace AF\ResourceHolders;

abstract class ResourceBrowser_BC extends ResourceGetter_BC {
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
}
