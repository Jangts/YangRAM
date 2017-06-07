<?php
namespace Studio\Pub\ResHolders;

class ContentProvider extends \AF\ResourceHolders\ContentProvider_BaseClass {
	protected $controllers = [
		'open'	=>	array(
			'classname'	=>	'OIViewController',
			'methods'	=>	array(
				'default'		=>	array(
					'methodname'	=>  'startpage',
					'minArgsLength'	=>	0
				),
				'gec'		=>	array(
					'minArgsLength'	=>	0
				),
				'spc'		=>	array(
					'minArgsLength'	=>	1
					/*
					 * string $preset spc preset alias
					 */
				)
			)
		)
	];
}
