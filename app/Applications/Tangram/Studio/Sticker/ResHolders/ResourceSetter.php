<?php
namespace Studio\Stk\ResHolders;

class ResourceSetter extends \AF\ResourceHolders\ResourceSetter_BC {
	protected $controllers = [
		'submit'	=>	array(
			'classname'	=>	'Submitter',
			'methods'	=>	array(
				'save'		=>	array(
					'minArgsLength'	=>	0
				),
				'remove'		=>	array(
					'minArgsLength'	=>	0
				)
			)
		)
	];
}
