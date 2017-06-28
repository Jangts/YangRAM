<?php
namespace Studio\Stk\Models\ListPageModels;

use Tangram\NIDO\DataObject;
use CMF\Models\EMC;
use AF\Util\OIML;

class Menu extends DataObject {
    public function __construct($localdict, $base){
		$localdict = $localdict->toArray();

		if(isset($base->group)){
			$group = '&group='.$base->group;
		}else{
			$group = '';
		}

		$data = [];
        $items = [];
        foreach($base->sortings as $key=>$sorting){
            $items[] = [
                'name'  =>  $localdict[$key],
                'attrs'	=>	$sorting,
                'href'	=>	'list/?sort='.$key.'&type='.$base->type.$group,
            ];
        }
		$data[] = [
            'name'      =>  $localdict["st"],
            'opened'    =>  'opened',
            'items'     =>  $items
        ];

		$items = [
			[
                'name'  =>  $localdict["al"],
                'attrs'	=>	$base->is_alltypes,
                'href'	=>	'list/?sort='.$base->sort.$group
            ]
		];
        foreach($base->types as $key=>$type){
            $items[] = [
                'name'  =>  $localdict["types"][$key]["desc"],
                'attrs'	=>	$type,
                'href'	=>	'list/?sort='.$base->sort.'&type='.$key.$group
            ];
        }
		$data[] = [
            'name'      =>  $localdict["tp"],
            'opened'    =>  'opened',
            'items'     =>  $items
        ];

		$items = [
			[
                'name'  =>  $localdict["al"],
                'attrs'	=>	$base->is_allclasses,
                'href'	=>	'list/?sort='.$base->sort.'&type='.$base->type
            ],
			[
                'name'  =>  $localdict["ug"],
                'attrs'	=>	$base->is_unclassified,
                'href'	=>	'list/?sort='.$base->sort.'&type='.$base->type.'&group'
            ]
		];

		$groups = EMC::groups(EMC::UNRECYCLED);
		foreach($groups as $row){
			if($row["groupname"]==$base->group){
				$currclass = 'curr';
			}else{
				$currclass = '';
			}
			$items[] = [
                'name'  =>  $row["groupname"],
                'attrs'	=>	$currclass,
                'href'	=>	'list/?sort='.$base->sort.'&type='.$base->type.'&group='.$row["groupname"]
            ];
		}
		$data[] = [
            'name'      =>  $localdict["gp"],
            'opened'    =>  'opened',
            'items'     =>  $items
        ];

		$this->data = $data;
    }
    
    public function render(){
        return OIML::menu($this->data);
    }
}
