<?php
namespace Studio\Stk\ResHolders;

class ContentProvider extends \AF\ResourceHolders\ContentProvider_BaseClass {
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
