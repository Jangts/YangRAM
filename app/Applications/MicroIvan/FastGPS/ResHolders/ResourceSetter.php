<?php
namespace GPS\ResHolders;

class ResourceSetter extends \AF\ResourceHolders\ResourceSetter_BC {
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
