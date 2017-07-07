<?php
namespace TC\Models\SheetsViews;

use AF\Util\OIML;
use Library\ect\Pager;

trait traits {
	private function getHead($names){
		$head = '<item class="head">';
		$head .= '<v class="sele"><el></el></v>';
		$head .= '<v class="name">'.$names["name"].'</v>';
		$head .= '<v class="time">'.$names["time"].'</v>';
		$head .= '<v class="back">'.$names["back"].'</v>';
		$head .= '<v class="dele">'.$names["dele"].'</v></item>';
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