<?php
namespace NFA\Watch;

class ContentProvider extends \AF\ResourceHolders\ContentProvider_BC {
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
