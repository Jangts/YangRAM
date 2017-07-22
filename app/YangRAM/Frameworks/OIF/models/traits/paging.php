<?php
namespace OIF\models\traits;

trait paging {
	public static function paging($data, $bgcolor = NULL){
		$names = $data["names"];
		$pages = $data["pages"];
		$path = $data["path"];
		if($bgcolor){
			$html = '<list type="pages" class="'.$bgcolor.'">';
		}else{
			$html = '<list type="pages">';
		}
		if($pages["length"] > 0){
			$html .= '<item class="pages-list-item"><click href="'.$path.'&page='.$pages["f"].'">'.$names["f"].'</click></item>';
			if($data["curr"]>$pages["f"]){
				$html .= '<item class="pages-list-item"><click href="'.$path.'&page='.$pages["p"].'">'.$names["p"].'</click></item>';
			}
			for ($n = 0; $n < $pages["length"]; $n++) {
				if ($pages[$n] == $data["curr"]) {
					$html .= '<item class="pages-list-item curr">'.$pages[$n].'</item>';
				}else{
					$html .= '<item class="pages-list-item"><click href="'.$path.'&page='.$pages[$n].'">'.$pages[$n].'</click></item>';
				}
			}
			if($data["curr"]<$pages["l"]){
				$html .= '<item class="pages-list-item"><click href="'.$path.'&page='.$pages["n"].'">'.$names["n"].'</click></item>';
			}
			$html .= '<item class="pages-list-item"><click href="'.$path.'&page='.$pages["l"].'">'.$names["l"].'</click></item>';
		}
		return $html.'</list>';
	}
}