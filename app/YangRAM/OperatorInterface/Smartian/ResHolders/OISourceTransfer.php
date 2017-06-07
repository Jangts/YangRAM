<?php
namespace Smartian\ResHolders;

use Status;
use Request;

class OISourceTransfer extends \AF\ResourceHolders\OISourceTransfer_BaseClass {
	protected $controllers = [
		'launch'	=>	array(
			'classname'	=>	'SmartianLauncher',
			'methods'	=>	array(
				'init'		=>	array(
					'minArgsLength'	=>	1
					/*
					* string $lang language
					*/
				),
				'welcome'		=>	array(
					'minArgsLength'	=>	1
					/*
					* string $lang language
					*/
				)
			)
		),
		'default'	=>	array(
			'classname'	=>	'OI\DefaultPage',
			'methods'	=>	array(
				'main'		=>	array(
					'minArgsLength'	=>	0
				)
			)
		)
	];

	public function getParameters(Request $request){
		if(AI_CURR==='SMARTIAN'){
			if(isset($request->PARAMS->lang)){
				return array($request->PARAMS->lang);
			}
			return $this->prtException(3);
		}
		return parent::getParameters($request);
	}
}
