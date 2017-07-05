<?php
namespace Studio\Pub\ResHolders;

class ResourceReceiver extends \AF\ResourceHolders\ResourceReceiver_BC {
	protected $controllers = [
		'submit'	=>	array(
			'classname'	=>	'Submitter',
			'methods'	=>	array(
				'sav'		=>	array(
					'minArgsLength'	=>	0
				),
				'pub'		=>	array(
					'minArgsLength'	=>	0
				),
				'rmv'		=>	array(
					'minArgsLength'	=>	0
				)
			)
		),
		'post'	=>	array(
			'classname'	=>	'Poster',
			'methods'	=>	array(
				'post'		=>	array(
					'minArgsLength'	=>	0
				),
				'update'	=>	array(
					'minArgsLength'	=>	0
				),
				'delete'	=>	array(
					'minArgsLength'	=>	0
				)
			)
		)
	];
}
