<?php
namespace OIF\models\traits;

trait sheets {
	public static function sheets($data, $bgcolor = NULL){
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
}