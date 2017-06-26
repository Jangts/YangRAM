<?php
namespace MSG\ResHolders;
use Status;

class OISourceTransfer extends \AF\ResourceHolders\OISourceTransfer_BC {
	protected $controllers = [
		'oimessages'	=>	array(
			'classname'	=>	'OIMessages',
			'methods'	=>	array(
				'notice'		=>	array(
					'minArgsLength'	=>	0
					/*
					* string $lang language
					*/
				)
			)
		)
	];
}
