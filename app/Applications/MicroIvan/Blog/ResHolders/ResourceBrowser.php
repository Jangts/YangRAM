<?php
namespace Blog\ResHolders;

use Tangram\ClassLoader;

class ResourceBrowser extends \AF\ResourceHolders\ResourceBrowser_BC {
	protected
	$classname = NULL,
	$controllers = [];

	public function __construct($app, $request){
		$filename = $app->Path.'Controllers/PageRenderer';
		$classname = $app->NAMESPACE.'Controllers\PageRenderer';
		ClassLoader::execute($filename);
		$class = new $classname($app, $request);
		$class->main();
	}
}