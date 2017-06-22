<?php
namespace Blog\ResHolders;

class ContentProvider extends \AF\ResourceHolders\ContentProvider_BaseClass {
	protected $controllers = [
		'pages'	=>	[
			'classname'	=>	'FE/Pages',
			'methods'	=>	[
				'main'		=>	[
					'methodname'	=> 'main',
					'minArgsLength'	=>	0
				],
				'id'		=>	[
					'methodname'	=> 'getPageById',
					'minArgsLength'	=>	1
				],
				'postname'	=>	[
					'methodname'	=> 'getPageByPostname',
					'minArgsLength'	=>	1
				],
				'roots'		=>	[
					'methodname'	=> 'main',
					'minArgsLength'	=>	0
				],
				'children'	=>	[
					'methodname'	=> 'getChildrenPages',
					'minArgsLength'	=>	1
				],
				'rss'	=>	[
					'methodname'	=> 'getRSSXML',
					'minArgsLength'	=>	0
				]
			]
		],
		'articles'	=>	[
			'classname'	=>	'FE/Articles',
			'methods'	=>	[
				'id'		=>	[
					'methodname'	=> 'getArticleById',
					'minArgsLength'	=>	1
				],
				'postname'	=>	[
					'methodname'	=> 'getArticleByPostname',
					'minArgsLength'	=>	1
				],
				'all'		=>	[
					'methodname'	=> 'getAllArticles',
					'minArgsLength'	=>	0
				],
				'day'		=>	[
					'methodname'	=> 'getArticlesOfDay',
					'minArgsLength'	=>	1
				],
				'month'		=>	[
					'methodname'	=> 'getArticlesOfMonth',
					'minArgsLength'	=>	1
				],
				'category'	=>	[
					'methodname'	=> 'getArticlesOfCategory',
					'minArgsLength'	=>	1
				]
			]
		],
		'categories'	=>	[
			'classname'	=>	'FE/Categories',
			'methods'	=>	[
				'id'		=>	[
					'methodname'	=> 'getCategoryById',
					'minArgsLength'	=>	1
				],
				'children'	=>	[
					'methodname'	=> 'getChildrenCategories',
					'minArgsLength'	=>	1
				],
				'menu'	=>	[
					'methodname'	=> 'getMenuFragment',
					'minArgsLength'	=>	0
				]
			]
		]
	];
}
