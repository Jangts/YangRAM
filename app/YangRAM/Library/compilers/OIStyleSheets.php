<?php
namespace Library\compilers;

class OIStyleSheets {
	private $appid, $appdir, $suitdir;

	private function cssPre($isMain){
		if($isMain){
			if(in_array($this->appid, ['SETTINGS', 'I4PLAZA', 'EXPLORER', 'TRASHCAN'])){
				return '#APP-'.$this->appid;
			}
			if($this->appid<10){
				return '#APP-000'.$this->appid;
			}
			if($this->appid<100){
				return '#APP-00'.$this->appid;
			}
			if($this->appid<1000){
				return '#APP-0'.$this->appid;
			}
			return '#APP-'.$this->appid;
		}else{
			return 'yangram application[appid="'.$this->appid.'"] content ';
		}
	}

	public function __construct($appid, $appdir, $suitdir){
		$this->appid = $appid;
		$this->appdir = $appdir;
		$this->suitdir = $suitdir;
	}

	public function cssFilter($str, $isMain = true){
		define('CSS_PRE', $this->cssPre($isMain));

		$str = preg_replace('/@charset[^;]*;/', '', $str);									//Delete Charset
		$str = preg_replace("/\/\*(.|\n)*?\*\//", "", $str);								//Delete Notes
		$str = preg_replace_callback('/([^{]+)(\{[^}]+\})/', function ($matches){
			$array = explode(',', $matches[1]);
			$selector = '';
			foreach($array as $str){
				$_str = str_replace('application', CSS_PRE, $str);
				if($_str==$str){
					$str = str_replace(CSS_PRE.' ', '', $str);
					$selector .= CSS_PRE.' '.$str.',';
				}else{
					$selector .= $_str;
				}
			}
			return $selector.$matches[2];
		}, $str);                                                           				//Add AppId
		$str = preg_replace('/\s*(\{|\}|:|;|,)\s*/', '$1', $str);							//Delete Space
		$str = preg_replace('/(:|;|,)/', '$1', $str);										//Add Space
		$str = preg_replace('/\s+/', ' ', $str);											//Trim Space
		$str = preg_replace('/(\r|\n)/', '', $str);											//Delete Break
		$str = preg_replace('/[;\s]*\}/', ";}\r\n", $str);									//Delete Break
		$str = preg_replace('/,\s*\{/', " {", $str);										//Delete Comma
		$str = str_replace('<__ADIR__>', $this->appdir, $str);
		$str = str_replace('<__SDIR__>', $this->suitdir,  $str);
		return $str;
	}
}
