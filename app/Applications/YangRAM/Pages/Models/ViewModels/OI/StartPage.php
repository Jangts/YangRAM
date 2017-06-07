<?php
namespace Pages\Models\ViewModels\OI;

use System\NIDO\DataObject;
use AF\ViewRenderers\OIML;

class Startpage extends DataObject {
	static
	$types = ["singlepage", "generalpage", "listpage", "detailpage", "userpage", "searchpage", "redirectings"],
	$abbrs = ["SNGL", "GNRL", "LIST", "DTAL", "USER", "SRCH", "RDIR"];


    public function __construct($localdict){
		$words = $localdict->labels;
		$this->data = [];
		foreach(self::$types as $i=>$type){
			$this->data[] = [
				'href'	=>	$type,
				'attrs'	=>	['data-tab-name'=>$type],
				'elem'	=>	[
					'mask'	=>	'',
					'titl'	=>	self::$abbrs[$i],
					'desc'	=>	$words["types"][$i+1]
				],
			];
		}
	}

	public function render(){
		return OIML::blocks($this->data, 'darkpurple');
	}
}
