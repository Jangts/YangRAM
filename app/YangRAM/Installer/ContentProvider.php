<?php
namespace Installer;

class ContentProvider extends \AF\ResourceHolders\ContentProvider_BaseClass {
	protected $controllers = [
		'message'	=>	array(
			'classname'	=>	'Message',
			'methods'	=>	array(
				'main'		=>	array(
					'minArgsLength'	=>	0
					/*
					* int $pid page id
					* string $preset preset alias, string $group group code, string $rq request
					* int $item content id
					*/
				)
			)
		)
	];
}
