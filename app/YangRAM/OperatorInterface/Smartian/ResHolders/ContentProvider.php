<?php
namespace Smartian\ResHolders;

use Status;
use Request;

class ContentProvider extends \AF\ResourceHolders\OISourceTransfer_BaseClass {
	protected $controllers = [
		'assistant'	=>	array(
			'classname'	=>	'Assistant',
			'methods'	=>	array(
				'search'		=>	array(
					'minArgsLength'	=>	1
					/*
					* int $oid, page id
					*/
				)
			)
		),
		'hello'	=>	array(
			'classname'	=>	'HelloWorld',
			'methods'	=>	array(
				'main'		=>	array(
					'minArgsLength'	=>	0
				)
			)
		)
	];
}
