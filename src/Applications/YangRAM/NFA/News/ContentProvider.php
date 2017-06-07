<?php
namespace NFA\News;

class ContentProvider extends \AF\ResourceHolders\ContentProvider_BaseClass {
	protected $controllers = [
		'wallwidgets'	=>	array(
			'classname'	=>	'WallWidgets',
			'methods'	=>	array(
				'messages'		=>	array(
					'minArgsLength'	=>	0
				)
			)
		)
	];
}
