<?php
namespace Studio\Dsr\Models;

use Tangram\NIDO\DataObject;
useAF\Models\Ect\THMI;
use AF\ViewRenderers\OIML;

class DefaultPageVM extends DataObject {
    public function __construct($localdict){
		$themes = THMI::all();
		$data = [];
		foreach($themes as $row){
			if($row->type==1){
				$href = 'launch://'.$this->appid.'::list/'.$row->alias;
			}else{
				$href = NULL;
			}
			if(strlen($row->alias) <= 7){
				$len = strlen($row->alias);
			}else{
				$len = 7;
			}
			
			$data[] = array(
				'href'	=>	$href,
				'attrs'	=>	[
					'x-theme-type'	=>	$row->type,
					'x-alias-lenth'	=>	$len
				],
				'elem'	=>	array(
					'mask'	=>	'',
					'titl'	=>	strtoupper(mb_substr($row->alias, 0, 7, 'utf-8')),
					'desc'	=>	$row->name,
				)
			);
		}
		$data[] = array(
			'href'	=>	'launch://8::shop/$themes/',
			'attrs'	=>	[
					'x-theme-type'	=>	3,
					'x-alias-lenth'	=>	4
				],
			'elem'	=>	array(
				'mask'	=>	'',
				'titl'	=>	'MORE',
				'desc'	=>	'Download Themes'
			)
		);
		$this->data = $data;
	}

	public function render(){
		return OIML::blocks($this->data, 'azure');
	}
}
