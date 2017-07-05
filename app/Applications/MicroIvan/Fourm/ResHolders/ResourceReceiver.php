<?php
namespace Fourm\ResHolders;

class ResourceReceiver extends \AF\ResourceHolders\ResourceReceiver_BC {
	protected $controllers = [
		'submit'	=>	[
			'classname'	=>	'Submitter',
			'methods'	=>	[
				'sav'		=>	[
					'methodname'	=>	'save',
					'minArgsLength'	=>	0
				],
				'dis'		=>	[
					'methodname'	=>	'disure',
					'minArgsLength'	=>	0
				],
				'use'		=>	[
					'minArgsLength'	=>	0
				],
				'del'		=>	[
					'minArgsLength'	=>	0
				]
			]
		]
	];
}
