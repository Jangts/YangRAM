<?php
namespace GPS\Models\ViewModels\OI;

use System\NIDO\DataObject;
use AF\ViewRenderers\OIML;

class Startpage extends DataObject {
	static $types = [ "singlepage", "index", "generalpage", "commonlist", "ataglist", "acatlist", "commondetail", "acatdetail"];

    public function __construct($localdict){
		$words = $localdict->labels;
		$this->data = [];
		foreach(self::$types as $i=>$type){
			$this->data[] = [
				'href'	=>	$type,
				'attrs'	=>	['data-tab-name'=>$type],
				'elem'	=>	[
					'mask'	=>	'',
					'titl'	=>	'FPAGE',
					'desc'	=>	$words["types"][$i+1]
				],
			];
		}
	}

	public function render(){
		return OIML::blocks($this->data, 'dark');
	}
}
