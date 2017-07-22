<?php
namespace OIF\models\traits;

trait blocks {
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
				$html .= '<v class="'.$key.'">'.$val.'</v>';
			}
			$html .= '</click>';
			if(isset($block["after"])){
				$html .= $block["after"];
			}
			$html .= '</item>';
		}
		return $html.'</list>';
    }
}