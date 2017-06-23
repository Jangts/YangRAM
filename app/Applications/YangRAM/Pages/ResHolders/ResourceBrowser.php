<?php
namespace Pages\ResHolders;

use Tangram\ClassLoader;

class ResourceBrowser extends \AF\ResourceHolders\ResourceBrowser_BaseClass {
	protected
	$classname = NULL,
	$controllers = [];

	public function __construct($app, $request){
		$filename = $app->Path.'Controllers/PageRenderer';
		$classname = $app->NAMESPACE.'Controllers\PageRenderer';
		ClassLoader::execute($filename);
		$class = new $classname($app, $request);
		$class->main($request->PARAMS->ok);
	}
}
