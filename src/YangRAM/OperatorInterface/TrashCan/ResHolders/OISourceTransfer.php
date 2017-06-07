<?php
namespace TC\ResHolders;

class OISourceTransfer extends \AF\ResourceHolders\OISourceTransfer_BaseClass {
	protected $controllers = [
		'default'	=>	array(
			'classname'	=>	'OIViewController\HomepageRenderer',
			'methods'	=>	array(
				'main'		=>	array(
					'minArgsLength'	=>	0
				),
				'lib'		=>	array(
					'minArgsLength'	=>	0
				),
				'spc'		=>	array(
					'minArgsLength'	=>	0
				),
				'xtd'		=>	array(
					'minArgsLength'	=>	0
				)
			)
		),
		'list'	=>	array(
			'classname'	=>	'OIViewController\ListpageRenderer',
			'methods'	=>	array(
				'lib'		=>	array(
					'minArgsLength'	=>	1
				),
				'spc'		=>	array(
					'minArgsLength'	=>	1
				),
				'xtd'		=>	array(
					'minArgsLength'	=>	1
				)
			)
		)
	];
}
