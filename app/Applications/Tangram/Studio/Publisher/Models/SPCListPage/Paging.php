<?php
namespace Studio\Pub\Models\SPCListPage;

use Tangram\NIDO\DataObject;
use AF\ViewRenderers\OIML;
use Library\ect\Pager;

class Paging extends DataObject {
    private $sort, $category, $status, $dir, $count, $curPage = 1, $prePage = 16;
    
    public function __construct($localdict, $base){
        $localdict = $localdict->list;
        $paging = new Pager($base->cpage, 9);
        $paging->setter($base->count, $base->prepage);
        $data = [
	        'names'	=>	[
	    	    'f'	=>	$localdict["fst"],
    	    	'l'	=>	$localdict["lst"],
	    	    'p'	=>	$localdict["pre"],
    	    	'n'	=>	$localdict["nxt"],
        	],
	        'pages'	=>	$paging->getData(),
    	    'curr'	=>	$base->cpage,
        	'path'	=>	$base->dirname.'?sort='.$base->sort.'&stts='.$base->stts.'&cat='.$base->category
        ];
        $this->data = $data;
    }
    
    public function render(){
        return OIML::paging($this->data, 'lightdatered');
    }
}