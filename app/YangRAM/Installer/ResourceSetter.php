<?php
namespace Installer;

class ResourceSetter extends \AF\ResourceHolders\ResourceSetter_BaseClass {
	protected $controllers = [
		'sta'	=>	array(
			'classname'	=>	'Starter',
			'methods'	=>	array(
				'l'		=>	array(
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