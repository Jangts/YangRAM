<?php
namespace GPS\ResHolders;

class ContentProvider extends \AF\ResourceHolders\ContentProvider_BaseClass {
	protected $controllers = [
		'open'	=>	[
			'classname'	=>	'OI\OpenMain',
			'methods'	=>	[
				'default'		=>	[
					'methodname'	=>  'main',
					'minArgsLength'	=>	0
				],
				'singlepage'		=>	[
					'minArgsLength'	=>	0
				],
				'index'		=>	[
					'minArgsLength'	=>	0
				],
				'generalpage'		=>	[
					'minArgsLength'	=>	0
				],
				'commonlist'		=>	[
					'minArgsLength'	=>	0
				],
				'ataglist'		=>	[
					'minArgsLength'	=>	0
				],
				'acatlist'		=>	[
					'minArgsLength'	=>	0
				],
				'commondetail'		=>	[
					'minArgsLength'	=>	0
				],
				'acatdetail'		=>	[
					'minArgsLength'	=>	0
				],
				'form'			=>	[
					'minArgsLength'	=>	0
				]
			]
		],
		'dialog'	=>	[
			'classname'	=>	'OI\ShowDailog',
			'methods'	=>	[
				'themes'		=>	[
					'minArgsLength'	=>	0
				],
				'theme'	=>	[
					'methodname'	=>	'templates',
					'minArgsLength'	=>	1
				]
			]
		],
		'renderer'	=>	[
			'classname'	=>	'PageRenderer',
			'methods'	=>	[
				'main'		=>	[
					'minArgsLength'	=>	1
					/*
					* int $pid page id
					* string $preset preset alias, string $group group code, string $rq request
					* int $item content id
					*/
				],
				'preview'	=>	[
					'minArgsLength'	=>	3
					/*
					* string $preset preset alias
					* string $theme theme of template
					* string $template
					*/
				]
			]
		],
		'pages'	=>	[
			'classname'	=>	'Pages',
			'methods'	=>	[
				'list'		=>	[
					'methodname'	=> 'getPageList',
					'minArgsLength'	=>	1
					/*
					* int $type page type [1-5]
					* string $format ['json', 'xml', 'serialize']
					*/
				],
				'info'		=>	[
					'methodname'	=> 'getPage',
					'minArgsLength'	=>	1
					/*
					* int $pid page id
					* string $format ['json', 'xml', 'serialize']
					*/
				],
				'render'	=>	[
					'minArgsLength'	=>	1
					/*
					* int $pid, page id
					* string $format ['json', 'xml', 'serialize']
					*/
				]
			]
		]
	];
}
