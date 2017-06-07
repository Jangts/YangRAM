<?php
namespace Studio\Stk\ResHolders;

class OISourceTransfer extends \AF\ResourceHolders\OISourceTransfer_BaseClass {
	protected $controllers = [
		'default'	=>	array(
			'classname'	=>	'OI\DefaultPageVC',
			'methods'	=>	array(
				'main'		=>	array(
					'minArgsLength'	=>	0
				)
			)
		),
		'list'	=>	array(
			'classname'	=>	'OI\ListPageVC',
			'methods'	=>	array(
				'main'		=>	array(
					'minArgsLength'	=>	0
				)
			)
		),
		'form'	=>	array(
			'classname'	=>	'OI\FormVC',
			'methods'	=>	array(
				'emc'		=>	array(
					'minArgsLength'	=>	1
				)
			)
		)
	];
}
