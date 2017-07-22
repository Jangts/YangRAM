<?php
namespace OIF\models\traits;

trait menu {
	public static function menu($data, $bgcolor = NULL){
		if($bgcolor){
			$html = '<list type="menu" class="'.$bgcolor.'">';
		}else{
			$html = '<list type="menu">';
		}
		foreach($data as $group){
			$html .= '<group opened="'.$group["opened"].'" ><itit class="category">'.$group["name"].'</itit>';
			foreach($group['items'] as $item){
				if(isset($item["attrs"])){
					if(is_array($item["attrs"])){
						$attrs = '';
						foreach($item["attrs"] as $attr=>$val){
							$attrs .= ' '.$attr.'="'.$val.'"';
						}
					}else{
						$attrs = ' '.$item["attrs"];
					}
				}else{
					$attrs = '';
				}
				if(empty($item["args"])){
					$html .= '<item'.$attrs.'><click href="'.$item["href"].'">'.$item["name"].'</click></item>';
				}else{
					$html .= '<item'.$attrs.'><click href="'.$item["href"].'" args="'.$item["args"].'">'.$item["name"].'</click></item>';
				}
			}
			$html .= '</group>';
		}
		return $html.'</list>';
	}
}