<?php
namespace YangRAM;

use Status;
use Response;

class UnitTester extends \AF\ResourceHolders\UnitTester_BaseClass {
	function myTestPHP($batch = false){
		if($batch) return true;

		//return 'Please delete or comment codes in this method after develop.';

		Response::instance()->sendheaders();
		phpinfo();
		exit;
	}

	function myTestSpeed($batch = false){
		//if($batch) return true;
		return 'Please look at the bottom of this page.';
	}

	function myTestForSCC($batch = false, $app, $request){
		if($batch) return true;
		$paths = $request->uri_path;
		$controller = new Controllers\SCCTester($app, $request);
		$methodname = isset($paths[4]) ? $paths[4] : 'main';
		if(method_exists($controller, $methodname)){
			return $controller->$methodname();
		}
		return 'PARAMETER_ERROR';
	}
}
