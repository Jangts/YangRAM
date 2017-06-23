<?php
namespace Smartian\ResHolders;

use Tangram\ClassLoader;

class ResourceBrowser extends \AF\ResourceHolders\ResourceBrowser_BaseClass {
	protected
	$classname = NULL,
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
		$filename = $app->Path.'Controllers/PageRenderer';
		$classname = $app->NAMESPACE.'Controllers\PageRenderer';
		ClassLoader::execute($filename);
		$class = new $classname($app, $request, $passport);
		$class->main($request->PARAMS->ok);
	}
}
