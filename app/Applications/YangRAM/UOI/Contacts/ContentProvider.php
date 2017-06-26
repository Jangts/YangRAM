<?php
namespace Contacts;

class ContentProvider extends \AF\ResourceHolders\ContentProvider_BC {
	protected $controllers = [
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
