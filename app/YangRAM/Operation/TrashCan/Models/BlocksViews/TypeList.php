<?php
namespace TC\Models\BlocksViews;
use Tangram\NIDO\DataObject;

class TypeList extends DataObject {
	private static
	$sorts = ['nma', 'nmd', 'mta', 'mtd'],
	$types = ['img', 'wav', 'doc', 'fld', 'txt', 'vod', 'gec'];

	private $sort, $type;
	
    public function __construct($localdict, $length, $uriarr, $presets, $extends, $select = 0){
		$localdict = $localdict->toArray();
        $this->data = [];
		switch($select){
			case 1:
			$this->getMaterials($localdict);
			break;
			case 2:
			$this->getContents($localdict, $presets);
			break;
			case 3:
			$this->getExtends($localdict, $extends);
			break;
			default:
			$this->getMaterials($localdict);
			$this->getContents($localdict, $presets);
			$this->getExtends($localdict, $extends);
		}
	}

	private function getMaterials($localdict){
		$html = '<itit href="default/lib/" class="category">'.$localdict["lib"].'</itit>';
		$types = ['emc', 'gec', 'fld', 'img', 'txt', 'wav', 'vod', 'doc'];
		foreach($types as $type){
			$html .= '<item type="'.$type.'" href="list/lib/'.$type.'/"><v class="icon"></v><v class="name">'.$localdict[$type].'</v></item>';
		}
		$this->data[] = $html;
	}
	
	private function getContents($localdict, $presets){
		$html = '<itit href="default/spc/" class="category">'.$localdict["spc"].'</itit>';
		foreach($presets as $row){
			$html .='<item type="spc" href="list/spc/'.$row->id.'/?sort=nma"><v class="icon"></v><v class="name">'.$row->item_type.'</v></item>';
		}
		$this->data[] = $html;
	}
	
	private function getExtends($localdict, $extends){
		$html = '<itit href="default/xtd/">'.$localdict["xtd"].'</itit>';
		foreach($extends as $row){
			$html .='<item type="xtd" href="list/xtd/'.$row->id.'/?sort=nma"><v class="icon"></v><v class="name">'.$row->typename.'</v></item>';
		}
		$this->data[] = $html;
	}

	public function render(){
		$list = '<section><list>';
		$list .= implode('</list><list>', $this->data);
		$list .= '</list></section>';
		return $list;
	}
}
