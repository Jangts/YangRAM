<?php
namespace Explorer\ResHolders;

class ContentProvider extends \AF\ResourceHolders\ContentProvider_BC {
	protected $controllers = [
		'fileinfo'	=>	array(
			'classname'	=>	'FileInfo',
			'methods'	=>	array(
				'img'		=>	array(
					'minArgsLength'	=>	1
					/*
					* string $ALIAS alias of picture
					*/
				),
				'txt'		=>	array(
					'minArgsLength'	=>	1
					/*
					* string $ALIAS alias of picture
					*/
				),
				'wav'		=>	array(
					'minArgsLength'	=>	1
					/*
					* string $ALIAS alias of picture
					*/
				)
			)
		),
		'folders'	=>	array(
			'classname'	=>	'Folders',
			'methods'	=>	array(
				'roots'		=>	array(
					'minArgsLength'	=>	0
				),
				'children'	=>	array(
					'minArgsLength'	=>	0
				)
			)
		)
	];
}
