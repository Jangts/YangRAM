<?php
namespace Studio\Pub\Models;

use Tangram\NIDO\DataObject;
use CM\SPC\Preset;
use AF\ViewRenderers\OIML;

class StartPage extends DataObject {
    public function __construct($localdict){
		$presets = Preset::all();
		$data = [];
		$data[] = array(
			'href'	=>	'launch://'.AI_CURR.'::gec/general/',
			'attrs'	=>	array(
                'data-tab-name' => 'general'
            ),
			'elem'	=>	array(
				'mask'	=>	'',
				'titl'	=>	'GENERAL',
				'desc'	=>	$localdict->gec
			)
		);
		foreach($presets as $row){
			$data[] = array(
				'href'	=>	'launch://'.AI_CURR.'::spc/'.$row->alias.'/',
                'attrs'	=>	array(
                    'data-tab-name' => $row->alias
                ),
				'elem'	=>	array(
					'mask'	=>	'',
					'titl'	=>	$row->code,
					'desc'	=>	$row->note
				)
			);
		}
		$data[] = array(
			'href'	=>	'launch://1::shop/presets/',
			'attrs'	=>	[],
			'elem'	=>	array(
				'mask'	=>	'',
				'titl'	=>	'MORE',
				'desc'	=>	'Download Presets'
			)
		);
        $this->data = $data;
	}

	public function render(){
		return OIML::blocks($this->data, 'datered');
	}
}
