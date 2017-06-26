<?php
namespace CMS\ResHolders;

class ContentProvider extends \AF\ResourceHolders\ContentProvider_BC {
	protected $controllers = [
		'spc'	=>	array(
			'classname'	=>	'SPContents',
			'methods'	=>	array(
				'get_list_by_preset'		=>	array(
					'minArgsLength'	=>	1
					/*
					* mixed $preset preset id or alias
					*/
				),
				'get_list_by_cat'		=>	array(
					'minArgsLength'	=>	1
					/*
					* mixed $preset preset id or alias
					*/
				)
			)
		),
		'usr'	=>	array(
			'classname'	=>	'Passports',
			'methods'	=>	array(
				'get_user_avatar'		=>	array(
					'minArgsLength'	=>	0
					/*
					* mixed $preset preset id or alias
					*/
				)
			)
		)
	];
}
