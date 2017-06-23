<?php
namespace Installer;

use Tangram\ClassLoader;
use Status;
use AF\Models\App as NAM;
use Library;

class ResourceBrowser extends \AF\ResourceHolders\ResourceBrowser_BaseClass {
	private
	$classname = NULL,
	$methodoptions = NULL,
	$controllers = [
		'renderer'	=>	array(
			'classname'	=>	'PageRenderer',
			'methods'	=>	array(
				'main'		=>	array(
					'minArgsLength'	=>	1
					/*
					* int $oid, page id
					*/
				),
				'preview'	=>	array(
					'minArgsLength'	=>	0
				)
			)
		)
	];

	public function __construct($app, $request, $passport){
		var_dump($request->COLUMN->__COL_ALIAS);
		die;
		$filename = $app->Path.'Controllers/PageRenderer';
		$classname = $app->NAMESPACE.'Controllers\PageRenderer';
		ClassLoader::execute($filename);
		$class = new $classname($app, $request, $passport);
		$class->main($request->PARAMS->ok);
	}


}
