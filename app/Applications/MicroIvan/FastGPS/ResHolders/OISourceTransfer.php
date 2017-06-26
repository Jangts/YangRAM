<?php
namespace GPS\ResHolders;

class OISourceTransfer extends \AF\ResourceHolders\OISourceTransfer_BC {
	protected $controllers = [
		'default'	=>	array(
			'classname'	=>	'OI\DefaultPage',
			'methods'	=>	array(
				'main'		=>	array(
					'minArgsLength'	=>	0
				),
				'startpage'		=>	array(
					'methodname'	=>	'main',
					'minArgsLength'	=>	0
				),
			)
		),
		'widgets'	=>	array(
			'classname'	=>	'I4PlazaWidgets',
			'methods'	=>	array(
				'aweek_pageviews'		=>	array(
					'minArgsLength'	=>	0
				)
			)
		)
	];
}
