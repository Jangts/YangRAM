<?php
namespace Explorer\ResHolders;

class OISourceTransfer extends \AF\ResourceHolders\OISourceTransfer_BaseClass {
	protected $controllers = [
		'default'	=>	array(
			'classname'	=>	'OIViewController\CommonViews',
			'methods'	=>	array(
				'main'		=>	array(
					'minArgsLength'	=>	0
				)
			)
		),
		'sch'	=>	array(
			'classname'	=>	'OIViewController\CommonViews',
			'methods'	=>	array(
				'main'		=>	array(
					'methodname'	=>	'search',
					'minArgsLength'	=>	0
				)
			)
		),
		'spc'	=>	array(
			'classname'	=>	'OIViewController\SPCViews',
			'methods'	=>	array(
				'main'		=>	array(
					'minArgsLength'	=>	0
				),
				'preset'		=>	array(
					'minArgsLength'	=>	0
				)
			)
		),
		'src'	=>	array(
			'classname'	=>	'OIViewController\SRCViews',
			'methods'	=>	array(
				'main'		=>	array(
					'minArgsLength'	=>	0
				),
				'all'		=>	array(
					'minArgsLength'	=>	0
				),
				'img'		=>	array(
					'minArgsLength'	=>	0
				),
				'txt'		=>	array(
					'minArgsLength'	=>	0
				),
				'doc'		=>	array(
					'minArgsLength'	=>	0
				),
				'wav'		=>	array(
					'minArgsLength'	=>	0
				),
				'vod'		=>	array(
					'minArgsLength'	=>	0
				),
				'zip'		=>	array(
					'minArgsLength'	=>	0
				),
				'ect'		=>	array(
					'minArgsLength'	=>	0
				),
				'sch'		=>	array(
					'minArgsLength'	=>	0
				)
			)
		)
	];
}
