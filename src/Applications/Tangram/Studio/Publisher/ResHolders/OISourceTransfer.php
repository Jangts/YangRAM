<?php
namespace Studio\Pub\ResHolders;

class OISourceTransfer extends \AF\ResourceHolders\OISourceTransfer_BaseClass {
	protected $controllers = [
		'default'	=>	array(
			'classname'	=>	'OIViewController',
			'methods'	=>	array(
				'main'		=>	array(
					'minArgsLength'	=>	0
				),
				'startpage'		=>	array(
					'methodname'	=>	'main',
					'minArgsLength'	=>	0
				),
			)
		),
	];
}
