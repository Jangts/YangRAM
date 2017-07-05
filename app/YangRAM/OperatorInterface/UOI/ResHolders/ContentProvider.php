<?php
namespace UOI\ResHolders;

use Status;
use Tangram\ClassLoader;
use Request;
use Application;
use AF\Models\Certificates\StdPassport;
use UOI\Controllers\VISA;

class ContentProvider extends \AF\ResourceHolders\ContentProvider_BC {
	protected $controllers = [
		'apps'	=>	[
			'classname'	=>	'Applications',
			'methods'	=>	[
				'arl'		=>	[
					'methodname'	=> 'AppRankingList',
					'minArgsLength'	=>	0
				],
				'icons'		=>	[
					'methodname' => 'icoReader',
					'minArgsLength'	=>	1
				]
			]
		],
		'wall'	=>	[
			'classname'	=>	'Memowall',
			'methods'	=>	[
				'marks'		=>	[
					'minArgsLength'	=>	0
				]
			]
		],
		'account'	=>	[
			'classname'	=>	'MyAccount',
			'methods'	=>	[
				'info'		=>	[
					'minArgsLength'	=>	0
					/*
					* string $lang language
					*/
				],
				'events'		=>	[
					'minArgsLength'	=>	1
					/*
					* string $lang language
					*/
				]
			]
		]
	];

	public function __construct(Application $app, Request $request){
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
