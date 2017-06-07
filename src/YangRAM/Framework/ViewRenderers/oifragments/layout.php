<?php
namespace AF\ViewRenderers\oifragments;

trait layout {
	public static function blocks($data, $bgcolor = NULL, $lying = false){
		if($lying){
			$lying = ' lying';
		}else{
			$lying = '';
		}
		if($bgcolor){
			$html = '<list type="blocks" class="'.$bgcolor.$lying.'">';
		}else{
			$html = '<list type="blocks" class="oiblocks'.$lying.'">';
		}
		foreach($data as $n=>$block){
			$html .= '<item>';
			if(isset($block["before"])){
				$html .= $block["before"];
			}
			if(isset($block["attrs"])){
				if(is_array($block["attrs"])){
					$attrs = '';
					foreach($block["attrs"] as $attr=>$val){
						$attrs .= ' '.$attr.'="'.$val.'"';
					}
				}else{
					$attrs = ' '.$block["attrs"];
				}
			}else{
				$attrs = '';
			}
			$html .= '<click href="'.$block["href"].'"'.$attrs.'>';
			foreach($block["elem"] as $key=>$val){
				$html .= '<vision class="'.$key.'">'.$val.'</vision>';
			}
			$html .= '</click>';
			if(isset($block["after"])){
				$html .= $block["after"];
			}
			$html .= '</item>';
		}
		return $html.'</list>';
	}

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

	public static function sheet($data, $bgcolor = NULL){
		if($bgcolor){
			$html = '<list type="sheet"  class="'.$bgcolor.'">';
		}else{
			$html = '<list type="sheet">';
		}
		$html .= '<itit>';
		$no = 0;
		foreach($data["head"] as $class=>$val){
			if(++$no%2==1){
				$html .= '<cell class="'.$class.' odd">'.$val.'</cell>';
			}else{
				$html .= '<cell class="'.$class.' even">'.$val.'</cell>';
			}
		}
		$html .= '</itit>';
		foreach($data["rows"] as $n => $row){
			if($n%2==1){
				$html .= '<item class="list odd">';
			}else{
				$html .= '<item class="list even">';
			}
			$no = 0;
			foreach($row as $class=>$val){
				if(++$no%2==1){
					$html .= '<cell class="'.$class.' odd">'.$val.'</cell>';
				}else{
					$html .= '<cell class="'.$class.' even">'.$val.'</cell>';
				}
			}
			$html .= '</item>';
		}
		return $html.'</list>';
	}

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
