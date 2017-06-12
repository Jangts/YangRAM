<?php
namespace Studio\Pub\Models\SPCListPage;

class Params {
    public
    $cpage = 1,
    $prepage = 16,
    $count = 0,
    $dirname,
    $sort,
    $sortings = array(
        'na'	=>	'',
        'nd'	=>	'',
        'ca'	=>	'',
        'cd'	=>	'',
        'ma'	=>	'',
        'md'	=>	'',
        'va'	=>	'',
        'vd'	=>	''
    ),
    $category,
    $stts,
    $status = array(
        'all'	=>	'',
        'pub'	=>	'',
        'unp'	=>	'',
        'pnd'	=>	'',
        'top'	=>	''
    ),
    $is_allclasses,
    $is_unclassified;
    
    public function __construct($presetinfo, $params, $uriarr, $length){
        $params = $params->toArray();
        $presetid = $presetinfo->id;

        if(!empty($params["page"])){
            $this->cpage = intval($params["page"]);
        }
        $this->dirname = '';
        for($i = 4; $i < $length; $i ++){
            $this->dirname .= $uriarr[$i].'/';
        }
        
        if(isset($params["sort"])&&array_key_exists($params["sort"], $this->sortings)){
            $this->sortings[$params["sort"]] = 'selected';
            $this->sort = $params["sort"];
        }else{
            $this->sortings["cd"] = 'selected';
            $this->sort = 'cd';
        }
        if(isset($params["stts"])&&array_key_exists($params["stts"], $this->status)){
            $this->status[$params["stts"]] = 'selected';
            $this->stts = $params["stts"];
        }else{
            $this->status["all"] = 'selected';
            $this->stts = 'all';
        }
        if(isset($params["cat"])&& is_numeric($params["cat"])){
            $this->is_allclasses = '';
            $this->is_unclassified = $params["cat"] == 0 ? 'selected' : '';
            $this->category = $params["cat"];
        }else{
            $this->is_allclasses = 'selected';
            $this->is_unclassified = '';
            $this->category = NULL;
        }
    }
    
    public function render(){
        return OIML::menu($this->data['side']);
    }
}