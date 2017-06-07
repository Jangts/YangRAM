<?php
namespace TC\ResHolders;
use Status;

class ResourceSetter extends \AF\ResourceHolders\OISourceTransfer_BaseClass {
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
