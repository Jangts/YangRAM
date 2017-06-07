<?php
namespace Settings\ResHolders;

class OISourceTransfer extends \AF\ResourceHolders\OISourceTransfer_BaseClass {
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
