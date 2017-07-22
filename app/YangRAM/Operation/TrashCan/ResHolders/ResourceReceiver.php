<?php
namespace TC\ResHolders;
use Status;

class ResourceReceiver extends \AF\ResourceHolders\OISourceTransfer_BC {
	protected $controllers = [
		'submit'	=>	array(
			'classname'	=>	'Submitter',
			'methods'	=>	array(
				'recover'		=>	array(
					'minArgsLength'	=>	0
				),
                'delete'		=>	array(
					'minArgsLength'	=>	0
				)
			)
		)
	];
}
