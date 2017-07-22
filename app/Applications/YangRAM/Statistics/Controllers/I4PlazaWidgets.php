<?php
namespace Statistics\Controllers;
use stdClass;
use Model;
use CMF\Models\SPC\Preset;
use CMF\Models\SPCLite;
use AF\Models\Util\GSTI;
use Library\localtimes\Timer;

class I4PlazaWidgets extends \OIF\controllers\I4PlazaWidgets_BC {
	public function hours(){
		$data = $this->get_default_data();
		$times = $this->get_time_parts();
		list($PV, $IP, $UV, $NV, $PT, $AG) = $this->get_list_data();
		
		$yesterday = date("Y-m-d", strtotime("-1 day"));
		foreach($times as $time){
			$results = GSTI::hours($yesterday, $time[0], $time[1]);
			$newers = GSTI::hours($yesterday, $time[0], $time[1], true);

			$IP["data"][] = $results['IP'];		
			$PV["data"][] = $results['PV'];		
			$UV["data"][] = $results['UV'];		
			$NV["data"][] = $newers['UV'];
		}
		
		$today = date("Y-m-d");
		foreach($times as $time){
			if(strtotime($today.' '.$time[0])<time()){
				$results = GSTI::hours($today, $time[0], $time[1]);
				$PT["data"][] = $results['PV'];	
			}
		}
		
		//$db->require = "DATE_FORMAT(accesstime, '%Y-%m-%d') = '".$yesterday."' AND is_mobile = 0";
		$AG["data"][0]["value"] = GSTI::statistics(GSTI::DAILY, GSTI::NO_MOBILE, GSTI::PV, strtotime("-1 day"));
		//$db->require = "DATE_FORMAT(accesstime, '%Y-%m-%d') = '".$yesterday."' AND is_mobile = 1";
		$AG["data"][1]["value"] = GSTI::statistics(GSTI::DAILY, GSTI::IS_MOBILE, GSTI::PV, strtotime("-1 day"));

		$data["series"][] = $IP;
		$data["series"][] = $UV;
		$data["series"][] = $NV;
		$data["series"][] = $PV;
		$data["series"][] = $PT;
		$data["series"][] = $AG;

		self::format([
			'type'		=>	'charts',
			'height'	=>	'280px',
			'data'		=>	[$data]
		]);
	}

	private function get_default_data(){
		return [
			'backgroundColor'	=>	'rgba(0,0,0,0.60)',
			'title'	=>	[
				'text'	=>	'YangRAM Statistics',
				'textStyle'	=>	[
					'fontSize'	=>	14,
				],
       			'subtext'	=>	'Yesterday Accesses',
        		'left'	=>	'center',
				'top'	=>	'1%'
			],
			'tooltip'	=>	new stdClass(),
			'legend'	=>	[
				'data'	=>	['IP', 'UV', 'NEW', 'PV', 'TODAY', 'AGENT'],
				'selected'	=>	[
            		'NEW'	=>	false,
            		'TODAY'	=>	false
        		],
				'textStyle'	=>	[
					'color'	=>	'#FFF',
					'fontStyle'	=>	'normal',
					'fontWeight'	=>	'normal',
					'fontSize'	=>	11,
				],
				'left'	=>	'center',
				'top'	=>	'89%'
    		],
			'grid'	=>	[
				'height'	=>	'52%',
				'top'		=>	'24%'
			],
			'xAxis'	=>	[
				'data'	=>	["1:30", "4:30", "7:30", "10:30", "13:30", "16:30", "19:30", "22:30"],
				'splitLine'	=>	[
					'show'	=>	false
				]
			],
			'yAxis'	=>	[
				'axisLine'	=>	[
					'show'	=>	false
				]
			],
			'series'	=>	[]
		];
	}

	private function get_time_parts(){
		return [
			["00:00:00", "02:59:59"],
			["03:00:00", "05:59:59"],
			["06:00:00", "08:59:59"],
			["09:00:00", "11:59:59"],
			["12:00:00", "14:59:59"],
			["15:00:00", "17:59:59"],
			["18:00:00", "20:59:59"],
			["21:00:00", "23:59:59"]
		];
	}

	private function get_list_data(){
		return [
			[
				'name'	=>	'PV',
				'type'	=>	'line',
				'data'	=>	[]
			],
			[
				'name'	=>	'IP',
				'type'	=>	'bar',
				'data'	=>	[]
			],
			[
				'name'	=>	'UV',
				'type'	=>	'bar',
				'data'	=>	[]
			],
			[
				'name'	=>	'NEW',
				'type'	=>	'bar',
				'data'	=>	[]
			],
			[
				'name'	=>	'TODAY',
				'type'	=>	'line',
				'data'	=>	[]
			],
			[
				'name'	=>	'AGENT',
				'type'	=>	'pie',
				'radius'	=>	'20%',
        		'center'	=>	['20%', '30%'],
				'data'	=>	[
					['value' => 0, 'name' => 'PC浏览器'],
            		['value' => 0,'name' => '移动端'],
				]
			]
		];
	}
}
