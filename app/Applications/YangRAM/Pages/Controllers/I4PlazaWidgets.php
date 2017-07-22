<?php
namespace Pages\Controllers;
use stdClass;
use Pages\Models\Data\Page;

class I4PlazaWidgets extends \OIF\controllers\I4PlazaWidgets_BC {
	public function aweek_pageviews(){
		$data = $this->get_default_data();
		$height = 100;
		$pages = Page::query(array('KEY_IS_RECYCLED'=>0,'KEY_STATE'=>1), [['KEY_COUNT', true]]);
		foreach($pages as $row){
			$data['yAxis']['data'][] = $row->name;
			$data['series'][0]['data'][] = [
				'value'	=>	$row->KEY_COUNT
			];
			$height += 15;
		}

		self::format([
			'type'		=>	'charts',
			'height'	=>	$height.'px',
			'data'		=>	[$data]
		]);
	}

	private function get_default_data(){
		return [
			'backgroundColor'	=>	'rgba(0,0,0,0.60)',
			'title'	=>	[
				'text'	=>	'YangRAM Pageviews',
				'textStyle'	=>	[
					'fontSize'	=>	14,	
				],
    	   		'subtext'	=>	'Cumulative Accesses',
    			'left'	=>	'center',
				'top'	=>	'1%'
			],
			'tooltip'	=>	new stdClass(),
			'grid'	=>	[
				'top'		=>	'80px'
			],
			'xAxis'	=>	[
				'type'	=>	'value',
				'position'	=>	'top',
				'splitLine'	=>	[
					'type'	=>	'dashed'
				]
			],
			'yAxis'	=>	[
				'type'	=>	'category',
				'data'	=>	[],
				'axisLabel'	=>	[
					'show'	=>	false
				],
				'axisLine'	=>	[
					'show'	=>	false
				]
			],
			'series'	=>	[
				[
					'name'	=>	'页面',
					'type'	=>	'bar',
					'data'	=>	[]
				]
			]
		];
	}
}
