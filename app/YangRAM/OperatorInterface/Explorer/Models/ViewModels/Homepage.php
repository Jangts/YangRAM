<?php
namespace Explorer\Models\ViewModels;
use Tangram\NIDO\DataObject;
use CMF\Models\SPC\Preset;

class Homepage extends DataObject {
    private static $srctypes = array('all', 'img', 'txt', 'doc', 'wav', 'vod', 'zip', 'ect');

    public function __construct($localdict, $datatype = 'default'){
        $this->data = [];
        if($datatype=='default'||$datatype=='spc'){
            $spc = array(
                array(
                    'attrs'  =>  array(
                        'x-href'    =>  'spc/',
                    'class'     =>  'category'
                    ),
                    'text'  =>  $localdict->spc
                )
            );
            $presets = Preset::all();
            foreach($presets as $preset){
                $spc[] = array(
                    'attrs'  =>  array(
                        'x-type'    =>  'spc',
                        'x-base'    =>  $preset->basic_type,
                        'x-href'    =>  'spc/preset/'.$preset->id.'/'
                    ),
                    'text'  =>  '<el>'.$preset->name.'</el>'
                );
            }
            $this->data[] = $spc;
        }
        
        if($datatype=='default'||$datatype=='src'){
            $src = array(
                array(
                    'attrs'  =>  array(
                        'x-href'    =>  'src/',
                        'class'     =>  'category'
                    ),
                    'text'  =>  $localdict->src
                )
            );
            foreach(self::$srctypes as $type){
                $src[] = array(
                        'attrs'  =>  array(
                            'x-type'    =>  'src'.$type,
                            'x-href'    =>  'src/'.$type.'/'
                        ),
                        'text'  =>  '<el>'.$localdict->$type.'</el>'
                    );
            }
            $this->data[] = $src;
        }
	}

    public function render(){
        $body  = '';
        foreach($this->data as $data){
            $body .= '<list type="inline">';
                foreach($data as $item){
                $body .= '<item';
                foreach($item['attrs'] as $attr=>$vsl){
                    $body .= ' '.$attr.'="'.$vsl.'"';
                }
                $body .= '>'.$item['text'].'</item>';
            }
            $body .= '</list>';
        }
        return $body;
	}
}
