<?php
namespace Statistics\ResHolders;

class OISourceTransfer extends \AF\ResourceHolders\OISourceTransfer_BaseClass {
	protected $controllers = [
		'default'	=>	array(
			'classname'	=>	'OI\DefaultPage',
			'methods'	=>	array(
				'main'		=>	array(
					'minArgsLength'	=>	0
				)
			)
		),
		'widgets'	=>	array(
			'classname'	=>	'I4PlazaWidgets',
			'methods'	=>	array(
				'hours'		=>	array(
					'minArgsLength'	=>	0
				)
			)
		)
	];
}
