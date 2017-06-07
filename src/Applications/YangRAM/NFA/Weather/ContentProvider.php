<?php
namespace NFA\Weather;

class ContentProvider extends \AF\ResourceHolders\ContentProvider_BaseClass {
	protected $controllers = [
		'wallwidgets'	=>	array(
			'classname'	=>	'WallWidgets',
			'methods'	=>	array(
				'dynamic_image'		=>	array(
					'minArgsLength'	=>	0
				)
			)
		)
	];
}
