<?php
namespace I4Plaza\ResHolders;

class ContentProvider extends \AF\ResourceHolders\ContentProvider_BaseClass {
	protected $controllers = [
		'module'	=>	array(
			'classname'	=>	'Modules',
			'methods'	=>	array(
				'widgets'		=>	array(
					'minArgsLength'	=>	0
				)
			)
		)
	];
}
