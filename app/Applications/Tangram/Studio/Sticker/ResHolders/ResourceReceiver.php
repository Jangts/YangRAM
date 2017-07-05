<?php
namespace Studio\Stk\ResHolders;

class ResourceReceiver extends \AF\ResourceHolders\ResourceReceiver_BC {
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
