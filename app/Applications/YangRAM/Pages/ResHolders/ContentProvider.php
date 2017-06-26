<?php
namespace Pages\ResHolders;

class ContentProvider extends \AF\ResourceHolders\ContentProvider_BC {
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
				'generalpage'		=>	[
					'minArgsLength'	=>	0
				],
				'listpage'		=>	[
					'minArgsLength'	=>	0
				],
				'detailpage'		=>	[
					'minArgsLength'	=>	0
				],
				'userpage'			=>	[
					'minArgsLength'	=>	0
				],
				'searchpage'		=>	[
					'minArgsLength'	=>	0
				],
				'redirectings'	=>	[
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
					'methodname'	=> 'getList',
					'minArgsLength'	=>	1
					/*
					* int $type page type [1-5]
					* string $format ['json', 'xml', 'serialize']
					*/
				],
				'info'		=>	[
					'methodname'	=> 'get',
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
