<?php
namespace Studio\Stk\Models;

use Tangram\NIDO\DataObject;
use CMF\Models\EMC;
use AF\Util\OIML;

class DefaultPageVM extends DataObject {
    public function __construct($localdict){
		$types = $localdict->types;
		$blocks = [];
		foreach($types as $type=>$row){
			$blocks[] = [
				'href'	=>	'launch://list/?type='.$type,
				'attrs'	=>	[],
				'elem'	=>	[
					'mask'	=>	'',
					'titl'	=>	$row["titl"],
					'desc'	=>	$row["desc"]
				]
			];
		}
		$this->data['blocks'] = $blocks;

		$tags = [];
		$colors = ['#df226e', '#d64a24', '#fc9432', '#1ba6ea', '#0dad51', '#ff668c', '#9a39b2'];
		$groups = EMC::groups(EMC::UNRECYCLED);
		foreach($groups as $row){
			$tags[] = [
				'href'		=>	'launch://list/?group='.urlencode($row["groupname"]),
				'name'		=>	$row["groupname"],
				'bgcolor'	=>	$colors[mt_rand(0, 6)]
			];
			
		}
		$this->data['tags'] = $tags;
	}

	public function render(){
		$html = OIML::blocks($this->data['blocks'], 'magenta');
		$html .= '<list class="custom-groups-list">';
		$html .= '<itit>EMC Label Groups</itit>';
		foreach($this->data['tags'] as $tag){
			$html .= '<item style="background-color: '.$tag['bgcolor'].';"><click href="'.$tag['href'].'">'.$tag['name'].'</click></item>';
		}
		$html .= '</list>';
		return $html;
	}
}
