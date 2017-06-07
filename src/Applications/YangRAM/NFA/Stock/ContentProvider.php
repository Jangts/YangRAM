<?php
namespace NFA\Stock;

class ContentProvider extends \AF\ResourceHolders\ContentProvider_BaseClass {
	protected $controllers = [
		'wallwidgets'	=>	array(
			'classname'	=>	'WallWidgets',
			'methods'	=>	array(
				'embed'		=>	array(
					'minArgsLength'	=>	0
				)
			)
		)
	];
}
