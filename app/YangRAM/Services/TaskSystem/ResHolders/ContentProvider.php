<?php
namespace Workers\ResHolders;

class ContentProvider extends \AF\ResourceHolders\ContentProvider_BC {
	protected $controllers = [
		'master'	=>	array(
			'classname'	=>	'Master',
			'methods'	=>	array(
				'main'		=>	array(
					'minArgsLength'	=>	0
				)
			)
		),
		'process'	=>	array(
			'classname'	=>	'Process',
			'methods'	=>	array(
				'main'		=>	array(
					'minArgsLength'	=>	0
				)
			)
		),
		'timer'	=>	array(
			'classname'	=>	'Timer',
			'methods'	=>	array(
				'main'		=>	array(
					'minArgsLength'	=>	0
				)
			)
		)
	];
}
