<?php
namespace TC\Models\MenuViews;

use Tangram\NIDO\DataObject;
use AF\Util\OIML;

class Side extends DataObject {
	private static
	$sorts = ['nma', 'nmd', 'mta', 'mtd'],
	$types = ['img', 'wav', 'doc', 'fld', 'txt', 'vod', 'gec', 'emc'];

	private $select ,$dir, $sort;
	
    public function __construct($localdict, $length, $uriarr, $sort, $presets, $rules){
        $this->data = [];
        if($length>4)  $this->select = $uriarr[4];
        $this->dir = '';
        for($i = 3; $i < $length; $i ++){
            $this->dir .= $uriarr[$i].'/';
        }
		$this->sort = $sort;
        $this->build($localdict, $length, $uriarr, $presets, $rules);
	}

	private function build($localdict, $length, $uriarr, $presets, $rules){
		$names = $localdict->toArray();
		$data = [];
        
        $items = [];
        foreach(self::$sorts as $sort){
			if($sort===$this->sort){
				$attrs = ['curr'=>'curr'];
			}else{
				$attrs = [];
			}
            $items[] = [
                'title' =>	$names[$sort],
                'name'  =>	$names[$sort],
                'attrs'	=>	$attrs,
                'href'	=>	$this->dir.'?sort='.$sort
            ];
        }
        $data[] = [
            'title'     =>  $names["sot"],
            'name'      =>  $names["sot"],
            'opened'    =>  'opened',
            'items'     =>  $items
        ];

		$items = [];
        foreach(self::$types as $type){
			if($length>5&&$uriarr[4]=='lib'&&($uriarr[5]===$type)){
				$attrs = ['curr'=>'curr'];
			}else{
				$attrs = [];
			}
            $items[] = [
                'title' =>	$names[$type],
                'name'  =>	$names[$type],
                'attrs'	=>	$attrs,
                'href'	=>	'list/lib/'.$type.'/?sort='.$this->sort
            ];
        }
		$data[] = [
            'title'     =>  $names["lib"],
            'name'      =>  $names["lib"],
            'opened'    =>  $this->select === 'lib' ? 'opened' : 'false',
            'items'     =>  $items
        ];

		$items = [];
        foreach($presets as $preset){
			if($length>5&&$uriarr[4]=='spc'&&($uriarr[5]===$preset->id)){
				$attrs = ['curr'=>'curr'];
			}else{
				$attrs = [];
			}
            $items[] = [
                'title' =>	$preset->item_type,
                'name'  =>	$preset->item_type,
                'attrs'	=>	$attrs,
                'href'	=>	'list/spc/'.$preset->id.'/?sort='.$this->sort
            ];
        }
		$data[] = [
            'title'     =>  $names["spc"],
            'name'      =>  $names["spc"],
            'opened'    =>  $this->select === 'spc' ? 'opened' : 'false',
            'items'     =>  $items
        ];

		$items = [];
        foreach($rules as $rule){
			if($length>5&&$uriarr[4]=='xtd'&&($uriarr[5]===$rule->id)){
				$attrs = ['curr'=>'curr'];
			}else{
				$attrs = [];
			}
            $items[] = [
                'title' =>	$rule->typename,
                'name'  =>	$rule->typename,
                'attrs'	=>	$attrs,
                'href'	=>	'list/xtd/'.$rule->id.'/?sort='.$this->sort
            ];
        }
		$data[] = [
            'title'     =>  $names["xtd"],
            'name'      =>  $names["xtd"],
            'opened'    =>  $this->select === 'xtd' ? 'opened' : 'false',
            'items'     =>  $items
        ];

        $this->data = $data;
	}

	public function render(){
		return OIML::menu($this->data, 'dark');
	}
}