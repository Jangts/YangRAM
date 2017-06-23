<?php
namespace Library\ect;

use PDO;
use RDO;
use Tangram\NIDO\DataObject;
use Library\formattings\ScalarFormat;

class SearchEngine {
	public $auxiliary = NULL;
	public $order = NULL;
	private $keywords;
	
	private $kwarray = [];
	private $rule = [];
	private $results = [];
	
	public function __construct($rule) {
		$this->rule = $rule;
	}
	
	public function search($words) {
		$this->segmentater($words);
		$rdo = new RDO;
		$rdo->using($this->rule["Table"]);

		$orders = $this->order ? $this->order : ($this->rule["Order"] ? $this->rule["Order"] : []);

		foreach($orders as $order){
			$rdo->orderby($order[0], $order[1]);
		}

		foreach($this->kwarray as $kw){
			$this->results[] = $this->match($rdo, $kw);
		}
	}
	
	private function segmentater($words){
		$this->keywords = $words;
		$this->kwarray[] = $words;
		$this->kwarray = array_unique(array_merge($this->kwarray, preg_split('/(\++|\s+)/', $words)));
	}
	
	public function getKeyWords(){
		return $this->kwarray;
	}
	
	private function match($rdo, $kw){
		$rdo->requiring($this->condition($kw));
		$result = $rdo->select();
		return $this->toArray($result, $kw);
	}
	
	private function condition($kw){
		$array = preg_split('/\,\s*/', $this->rule["Fields"]);
		$add = $this->rule["Auxiliary"];
		if($this->auxiliary){
			foreach($this->auxiliary as $key=>$val){
				$add .= " AND $key = '$val'";
			}
		}
		$add = preg_replace('/^\s*AND\s*/', '', $add);
		foreach($array as $i=>$key){
			if($i==0) $require = "($key LIKE '%".$kw."%'";
			else $require .= " OR $key LIKE '%".$kw."%'";
		}
		if(count($array)>0) $require .= ")";
		if($add!="") $require .=" AND ($add)";
		return $require;
	}
	
	private function toArray($result, $kw){
		$index = $this->rule["Index"];
		$array = [];
		if($result){
			$pdos = $result->getPDOStatement();
            while($row = $pdos->fetch(PDO::FETCH_ASSOC)){
                if(self::filter($row, $kw)){
					$key = strval($row[$index]);
					$array[$key] = $row;
				}				
			}
		}
		return $array;
	}
	
	private static function truekey($key){
		$array = preg_split('/\$*\.\$*/', $key);
		return end($array);
	}

	private function filter($row, $kw){
		if(empty($kw)){
			return false;
		}		
		$array = preg_split("/\,\s*/", $this->rule["Fields"]);
		foreach($array as $key){
			$key = self::truekey($key);
			$str = strip_tags(htmlspecialchars_decode($row[$key]));
			if($str&&stristr($str, $kw)){
				return true;
			}
		}
		return false;
	}
	
	public function getRS() {
		$array1 = [];
		$array2 = [];
		foreach($this->results as $result){
			foreach($result as $index=>$rs){
				if(!in_array($index, $array1)){
					$array1[] = $index;
					$array2[] = $rs;
				}
			}
		}
		return $array2;
	}
	
	public function getMarkedRS($tn = 25, $dn = 200){
		$search = '/('.implode("|", $this->kwarray).')/i';
		$results = self::getRS();
		$rs = [];
		foreach($results as $i=>$row){
			$rs[$i] = $row;
			$rs[$i][$this->rule["Title"]] = preg_replace_callback($search, array($this, 'addTagsForKeyWords'), ScalarFormat::subString($row[$this->rule["Title"]], $tn));
			if(isset($row[$this->rule["Desc"]])){
				$rs[$i][$this->rule["Desc"]] = preg_replace_callback($search, array($this, 'addTagsForKeyWords'), ScalarFormat::subString($row[$this->rule["Desc"]], $dn));
			}
		}
		return $rs;
	}
	
	private function addTagsForKeyWords($matches){
		return '<kw>'.$matches[1].'</kw>';
	}
	
	public static function getPointRS($string, $length, $words, $color = 'red'){
		$search = '/('.implode("|", $words).')/i';
		return preg_replace($search, '<font color="'.$color.'">$1</font>', ScalarFormat::subString($string, $length));
	}
}