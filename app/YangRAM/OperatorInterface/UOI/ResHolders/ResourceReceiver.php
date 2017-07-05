<?php
namespace UOI\ResHolders;

class ResourceReceiver extends \AF\ResourceHolders\ResourceReceiver_BC {
	protected $controllers = [
		'visa'	=>	array(
			'classname'	=>	'VISA',
			'methods'	=>	array(
				'logon'		=>	array(
					'minArgsLength'	=>	0
				),
				'logondesktop'		=>	array(
					'minArgsLength'	=>	0
				),
				'logoff'	=>	array(
					'minArgsLength'	=>	0
				),
				'lock'	=>	array(
					'minArgsLength'	=>	0
				),
				'checkpin'	=>	array(
					'minArgsLength'	=>	1
				)
			)
		)
	];
}
