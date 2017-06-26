<?php
namespace Studio\Tsk;
use Status;

class OISourceTransfer extends \AF\ResourceHolders\OISourceTransfer_BC {
	protected $controllers = [
		'default'	=>	array(
			'classname'	=>	'OI\DefaultPage',
			'methods'	=>	array(
				'main'		=>	array(
					'minArgsLength'	=>	0
				)
			)
		)
	];
}
