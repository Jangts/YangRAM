<?php
namespace TC\Models\SheetsViews;

use AF\ViewRenderers\OIML;
use Library\ect\Pager;

trait traits {
	private function getHead($names){
		$head = '<item class="head">';
		$head .= '<vision class="sele"><el></el></vision>';
		$head .= '<vision class="name">'.$names["name"].'</vision>';
		$head .= '<vision class="time">'.$names["time"].'</vision>';
		$head .= '<vision class="back">'.$names["back"].'</vision>';
		$head .= '<vision class="dele">'.$names["dele"].'</vision></item>';
		return $head;
	}

    private function buildPagesList($localdict){
		if($this->count){
			$names = $localdict->toArray();
        	$paging = new Pager($this->cpage, 9);
        	$paging->setter($this->count, $this->prePage);
        	$data = array(
            	'names'	=>	array(
                	'f'	=>	$names["fst"],
                	'l'	=>	$names["lst"],
                	'p'	=>	$names["pre"],
                	'n'	=>	$names["nxt"],
            	),
            	'pages'	=>	$paging->getData(),
            	'curr'	=>	$this->cpage,
            	'path'	=>	$this->dir.'?sort='.$this->sort.'&stts='.$this->status.'&cat='.$this->category
        	);
        	$this->data[] = OIML::paging($data, 'dark');
		}
		
	}
}