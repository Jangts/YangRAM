<?php
namespace CMS\ResHolders;
use Status;

class OISourceTransfer extends \AF\ResourceHolders\OISourceTransfer_BC {
	protected $controllers = [
		'widgets'	=>	array(
			'classname'	=>	'I4PlazaWidgets',
			'methods'	=>	array(
				'new_contents'		=>	array(
					'minArgsLength'	=>	0
				)
			)
		)
	];
}
