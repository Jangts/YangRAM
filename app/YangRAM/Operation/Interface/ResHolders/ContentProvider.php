<?php
namespace UOI\ResHolders;

use Status;
use Tangram\ClassLoader;
use Request;
use Application;
use AF\Models\Certificates\StdPassport;
use UOI\Controllers\System;
use UOI\Controllers\VISA;



class ContentProvider extends \AF\ResourceHolders\ContentProvider_BC {
	// protected $controllers = [
	// 	'account'	=>	[
	// 		'classname'	=>	'MyAccount',
	// 		'methods'	=>	[
	// 			'info'		=>	[
	// 				'minArgsLength'	=>	0
	// 				/*
	// 				* string $lang language
	// 				*/
	// 			],
	// 			'events'		=>	[
	// 				'minArgsLength'	=>	1
	// 				/*
	// 				* string $lang language
	// 				*/
	// 			]
	// 		]
	// 	],
	// 	'apps'	=>	[
	// 		'classname'	=>	'Applications',
	// 		'methods'	=>	[
	// 			'arl'		=>	[
	// 				'methodname'	=> 'AppRankingList',
	// 				'minArgsLength'	=>	0
	// 			],
	// 			'icons'		=>	[
	// 				'methodname' => 'icoReader',
	// 				'minArgsLength'	=>	1
	// 			]
	// 		]
	// 	],
	// 	'account'	=>	[
	// 		'classname'	=>	'MyAccount',
	// 		'methods'	=>	[
	// 			'info'		=>	[
	// 				'minArgsLength'	=>	0
	// 				/*
	// 				* string $lang language
	// 				*/
	// 			],
	// 			'events'		=>	[
	// 				'minArgsLength'	=>	1
	// 				/*
	// 				* string $lang language
	// 				*/
	// 			]
	// 		]
	// 	],
	// 	'wall'	=>	[
	// 		'classname'	=>	'Memowall',
	// 		'methods'	=>	[
	// 			'marks'		=>	[
	// 				'minArgsLength'	=>	0
	// 			]
	// 		]
	// 	]
	// ];

	public function __construct(Application $app, Request $request){
		define('UOI_DIR',   	I4S_DIR.'Operation/Interface/');
        define('UOI_PID',   	PID.UOI_DIR);
        define('PATH_UOI',      ROOT.UOI_DIR);

		define('__UOI_DIR', __DIR._WEBUOI_);

        ClassLoader::setNSMap([
			'OIF'	=>  PATH_FMWK.'OIF/'				
		]);
		
		if($request->LENGTH===3){
			return $this->interface($app, $request);
		}
		return $this->resource($app, $request);
	}

	public function interface($app, $request){
		$passport = StdPassport::instance();
		$system = new System($app, $request);
		$visa = new VISA($app, $request, $passport);
		$status = (string) $visa->myStatus();
		//var_dump($status);
		//die;
		switch ($status) {
			case 'Runholder':
			$system->loadInterface($passport);
			break;
			case 'Member':
			case 'Familiar':
			case 'Acquaintance':
			case 'Guest':
			$system->loadLoginPanel();
			break;
			default:
			new Status(700.6, '', 'Unknow Visa Status!' . $status, true);
		}
	}

	public function resource($app, $request){
		if(isset($request->uri_path[3])&&$request->uri_path[3]==='scripts'){
			$filename = $app->Path.'Controllers/Runtime';
			$classname = $app->NAMESPACE.'Controllers\Runtime';
			if(isset($request->uri_path[4])){
				if($request->uri_path[4]==='system.js'){
					$methodname = 'sys';
					$language = $GLOBALS['NEWIDEA']->LANGUAGE;
				}elseif(isset($request->uri_path[6])&&$request->uri_path[6]==='runtime.js'){
					$methodname = $request->uri_path[4] === 'evn' ? 'evn' : 'log';
					$language = $request->uri_path[5];
				}else{
					new Status(404, true);
				}
				ClassLoader::execute($filename);
				$class = new $classname($app, $request, StdPassport::instance());
				return $class->$methodname($language);
			}
			new Status(404, true);
		}
		$visa = new VISA($app, $request);
		$status = (string) $visa->myStatus();
		if($status==='Runholder'){
			$classname = $this->getClassName($request);
			$filename = $app->Path.'Controllers/'.$classname;
			$classname = $app->NAMESPACE.'Controllers\\'.$classname;
			$methodname = $this->getMethodName($request);
			$arguments = $this->getParameters($request);
			ClassLoader::execute($filename);
			$class = new $classname($app, $request, StdPassport::instance());
			call_user_func_array([$class, $methodname], $arguments);
		}else{
            new Status(700.7, '', 'Current Status [ '.$status.' ]',true);
        }
	}
}
