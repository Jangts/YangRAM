<?php
namespace Studio\Pub\Models\SPCListPage;

use System\NIDO\DataObject;
use CM\SPC\Category;
use AF\ViewRenderers\OIML;

class Menu extends DataObject {    
    public function __construct($localdict, $presetinfo, $base){
        $localdict = $localdict->side;
        $presetid = $presetinfo->id;

        $data = [];
        $items = [];
        foreach($base->sortings as $key=>$sorting){
            $items[] = [
                'name'  =>  $localdict[$key],
                'attrs'	=>	$sorting,
                'href'	=>	$base->dirname.'?sort='.$key.'&stts='.$base->stts.'&cat='.$base->category
            ];
        }
        $data[] = [
            'name'      =>  $localdict["st"],
            'opened'    =>  'false',
            'items'     =>  $items
        ];

        $items = [];
        foreach($base->status as $key=>$status){
            $items[] = [
                'name'  => $localdict[$key],
                'attrs'	=>	$status,
                'href'	=>	$base->dirname.'?sort='.$base->sort.'&stts='.$key.'&cat='.$base->category
            ];
        }
        $data[] = [
            'name'      =>  $localdict["cdt"],
            'opened'    =>  'opened',
            'items'     =>  $items
        ];

        $items = [];
        $items[] = [
            'name'  =>  $localdict["all"],
            'attrs'	=>	$base->is_allclasses,
            'href'	=>	$base->dirname.'?sort='.$base->sort.'&stts='.$base->stts.'&cat=null'
        ];
        $items[] = [
            'name'  =>  $localdict["uc"],
            'attrs'	=>	$base->is_unclassified,
            'href'	=>	$base->dirname.'?sort='.$base->sort.'&stts='.$base->stts.'&cat=0'
        ];
        $cats = Category::byType($presetid);
        foreach($cats as $row){
            if($row->id==$base->category){
                $currclass = 'selected';
            }else{
                $currclass = '';
            }
            $items[] = [
                'name'  => $row->name,
                'attrs'	=>	$currclass,
                'href'	=>	$base->dirname.'?sort='.$base->sort.'&stts='.$base->stts.'&cat='.$row->id
            ];
        }
        $data[] = [
            'name'      =>  $localdict["ct"],
            'opened'    =>  'false',
            'items'     =>  $items
        ];
        $this->data = $data;
    }
    
    public function render(){
        return OIML::menu($this->data);
    }
}