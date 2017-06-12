<?php
namespace GPS\ResHolders;

class ResourceTransfer extends \AF\ResourceHolders\ResourceTransfer_BaseClass {
	protected
	$classname = NULL,
	$controllers = [
		'g'	=>	'FE\GECPageRenderer',
		'p'	=>	'FE\ONEPageRenderer',
		's'	=>	'FE\SPCPageRenderer'
	];
}