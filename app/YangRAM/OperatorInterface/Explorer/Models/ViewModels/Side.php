<?php
namespace Explorer\Models\ViewModels;

use Tangram\NIDO\DataObject;
use CM\SPC\Preset;

class Side extends DataObject {
    private static $srctypes = ['all', 'img', 'txt', 'doc', 'wav', 'vod', 'zip', 'ect'];

    public function __construct($localdict, $menus, $datatype, $itemtype, $folder = ''){
        $data = [];
        $data[] = [
            'attrs'  =>  [
                'x-href'    =>  'spc/',
                'class'     =>  'category'
            ],
            'text'  =>  $localdict->spc
        ];
        $presets = Preset::all();
        foreach($presets as $preset){
            if($preset->id==$itemtype){
                $data[] = [
                    'attrs'  =>  [
                        'x-type'    =>  'spc',
                        'x-base'    =>  $preset->basic_type,
                        'x-href'    =>  'spc/preset/'.$preset->id,
                        'selected'    =>  'true'
                    ],
                    'text'  =>  '<el>'.$preset->name.'</el>'
                ];
            }else{
                $data[] = [
                    'attrs'  =>  [
                        'x-type'    =>  'spc',
                        'x-base'    =>  $preset->basic_type,
                        'x-href'    =>  'spc/preset/'.$preset->id.'/'
                    ],
                    'text'  =>  '<el>'.$preset->name.'</el>'
                ];
            }
        }
        $data[] = [
            'attrs'  =>  [],
            'text'  =>  '<click href="1::shop/presets/">'.$localdict->downspc.'</click>'
        ];
        $data[] = [
            'attrs'  =>  [
                'x-href'    =>  'src/',
                'class'     =>  'category'
            ],
            'text'  =>  $localdict->src
        ];
        if($datatype=='src'){
				$folder = $folder.'/';
		}else{
			$folder = '';
		}
        foreach(self::$srctypes as $type){
            if($type==$itemtype){
                $data[] = [
                    'attrs'  =>  [
                        'x-type'    =>  'src'.$type,
                        'x-href'    =>  'src/'.$type.'/'.$folder,
                        'selected'    =>  'true'
                    ],
                    'text'  =>  '<el>'.$localdict->$type.'</el>'
                ];
            }else{
                $data[] = [
                    'attrs'  =>  [
                        'x-type'    =>  'src'.$type,
                        'x-href'    =>  'src/'.$type.'/'.$folder
                    ],
                    'text'  =>  '<el>'.$localdict->$type.'</el>'
                ];
            }
        }
        
        $this->data = $data;
	}
}
