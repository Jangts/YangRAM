<?php
namespace UOI\ResHolders;

class ResourceSetter extends \AF\ResourceHolders\ResourceSetter_BaseClass {
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
