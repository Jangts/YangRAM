<?php
namespace Studio\Stk\Models\ListPageModels;

class Params {
    public
    $curpage = 1,
    $prepage = 16,
    $count = 0,
    $dirname,
    $sort,
    $sortings = array(
        'na'	=>	'',
		'nd'	=>	'',
		'ga'	=>	'',
		'gd'	=>	'',
		'ma'	=>	'',
		'md'	=>	''
    ),
    $group,
    $type,
    $types = array(
		'1'	=>	'',
		'2'	=>	'',
		'3'	=>	'',
		'4'	=>	'',
		'5'	=>	''
	),
    $is_alltypes,
    $is_allclasses,
    $is_unclassified;
    
    public function __construct($params){
        $params = $params->toArray();

        if(isset($params["sort"])&&isset($this->sortings[$params["sort"]])){
			$this->sortings[$params["sort"]] = 'selected';
			$this->sort = $params["sort"];
		}else{
			$this->sortings["na"] = 'selected';
			$this->sort = 'na';
		}
		if(isset($params["type"])&&isset($this->types[$params["type"]])){
			$this->is_alltypes = '';
			$this->types[$params["type"]] = 'selected';
			$this->type = $params["type"];
		}else{
			$this->is_alltypes = 'selected';
			$this->type = 0;
		}
		if(isset($params["group"])){
			$this->is_allclasses = '';
			$this->is_unclassified = $params["group"] == '' ? 'selected' : '';
			$this->group = $params["group"];
		}else{
			$this->is_allclasses = 'selected';
			$this->is_unclassified = '';
			$this->group = NULL;
		}
    }
    
    public function render(){
        return OIML::menu($this->data['side']);
    }
}