<?php
namespace CMS\Controllers;

use Model;
use CM\SPC\Preset;
use CM\SPCLite;

class I4PlazaWidgets extends \OIC\I4PlazaWidgets_BC {
	public function new_contents(){
        $contents = SPCLite::getList(NULL, NULL, SPCLite::PUBLISHED, SPCLite::PUBTIME_DESC, 0, 5, Model::LIST_AS_ARR);
		//$result = Contents::getNoDifferenceContents(1, $CTT_SortType = 3, $start = 0, $num = 5);
		$presets = Preset::all();
		$types = [];
		foreach($presets as $preset){
			$types[$preset->alias] = array(
				'item_type'	=>	$preset->item_type,
				'app_id'	=>	$preset->app_id
			);
		};
		$array = [];
		$this->loaderLocalTimer('zh-cn');
		foreach($contents as $row){
			if(isset($types[$row["SET_ALIAS"]])){
				$array[] = array(
				'MARK'	=>	$types[$row["SET_ALIAS"]]["item_type"],
				'TITLE'	=>	$row["TITLE"],
				'TIME'	=>	$this->timer->format($row["PUBTIME"], $format = "m/d H:i")
			);
			}
			
		}
		$data = array(
			'type'		=>	'stripe',
			'height'	=>	'30px',
			'data'		=>	$array
		);
		self::format($data);
	}
}
