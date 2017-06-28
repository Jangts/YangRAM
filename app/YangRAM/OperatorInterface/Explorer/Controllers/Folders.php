<?php
namespace Explorer\Controllers;

use Controller;
use CMF\Models\SRC\Folder;

class Folders extends Controller {
    private static function render($array, $level = 1){
		$list = '';
		$left = $level* 20;
		foreach($array as $row){
            $row = $row->toArray();
			if(self::checkChildren($row["id"])){
				$hasChildren = 'has-child';
			}else{
				$hasChildren = '';
			}
			$list .= '<item level="'.$level.'" fldid="'.$row["id"].'" class="'.$hasChildren.'">';
			$list .= '<vision class="folder-item" style="padding-left:'.$left.'px"><em class="folder-operate-icon"></em>';
			$list .= '<el class="folder-item-icon"></el>';
			$list .= '<el class="folder-item-name">'.$row["name"].'</el></vision>';
			$list .= '<list class="folder-tree hidden"></list></item>';
		}
		return $list;
	}

    private static function checkChildren($parent){
		if($children = Folder::children($parent)){
			return count($children);
		}
		return 0;
    }

    public function roots(){
        $array = Folder::roots(Folder::NAME_ASC_GBK);
		if(count($array)){
			$hasChildren = 'has-child';
		}else{
			$hasChildren = '';
		}
		echo '<list class="folder-tree">';
		echo '<item level="0" fldid="5" class="'.$hasChildren.' expand" selected>';
		echo '<vision class="folder-item" style="padding-left:0px"><em class="folder-operate-icon"></em>';
		echo '<el class="folder-item-icon"></el>';
		echo '<el class="folder-item-name">Operators</el></vision>';
		echo '<list class="folder-tree">';
		echo self::render($array, 1);
		echo '</list></item></list>';
	}

	public function children(){
		$post = $this->request->FORM->toArray();
		$fldid = $post["fldid"];
		$level = $post["level"] + 1;
		$array = Folder::children($fldid);
		echo self::render($array, $level);
	}
}
