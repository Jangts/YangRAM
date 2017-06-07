<?php
namespace Library\ect;

use RDO;

Class Pager {
	private $cpage = 1;
	private $itemNum = 9;
	private $pageNum;
	
	public function __construct($cpage = NULL, $itemNum = NULL) {
		if($cpage){
			$this->cpage = $cpage > 0 ? $cpage : 1;
		}
		if($itemNum){
			$this->itemNum = $itemNum;
		}
	}
	
	public function query($table, $require = 'KEY_IS_RECYCLED = 0', $prePage = 7) {
		$rdo = new RDO;
		$rdo->using($table)->requiring($require);
		$total = $rdo->count(1);
		$prePage = $prePage or 7;
		$this->pageNum = ceil($total / $prePage);
		$this->cpage = $this->pageNum < $this->cpage ? $this->pageNum : $this->cpage;
	}
	
	public function setter($total, $prePage = 7) {
		$prePage = $prePage or 7;
		$this->pageNum = ceil($total / $prePage);
	}
	
	public function getData(){
		$return = [];
		$return["c"] = $this->cpage;
		$return["f"] = 1;
		$return["p"] = $this->cpage > 1 ? $this->cpage - 1 : 1;
		$return["n"] = $this->cpage < $this->pageNum ? $this->cpage + 1 : $this->pageNum;
		$return["l"] = $this->pageNum;
		$return["s"] = $this->cpage > (ceil($this->itemNum / 2) - 1) ? $this->cpage - ceil($this->itemNum / 2) + 1 : 1;
		$return["e"] = $this->pageNum - $this->cpage > floor($this->itemNum / 2) ? $this->cpage + floor($this->itemNum / 2) : $this->pageNum;
		$return["length"] = 0;
		for ($n = $return["s"]; $n <= $return["e"]; $n++) {
			$return[] = $n;
			$return["length"]++;
		}
		return $return;
	}
	
	public function getList($pre = 'Prev', $nxt = 'Next', $stt = NULL, $end = NULL){
		$pages = $this->getData();
		$html = '';
		if($pages["length"] > 0){
			if($stt){
				$html .= '<li class="pages-list-item" onclick="window.location.href=\'?page='.$pages["f"].'\'">'.$stt.'</li>';
			}
			if($this->cpage>$pages["f"]){
				$html .= '<li class="pages-list-item" onclick="window.location.href=\'?page='.$pages["p"].'\'">'.$pre.'</li>';
			}
			for ($n = 0; $n < $pages["length"]; $n++) {
				if ($pages[$n] == $this->cpage) {
					$html .= '<li class="pages-list-item curr" onclick="window.location.href=\'?page='.$pages[$n].'\'">'.$pages[$n].'</li>';
				}else{
					$html .= '<li class="pages-list-item" onclick="window.location.href=\'?page='.$pages[$n].'\'">'.$pages[$n].'</li>';
				}
			}
			if($this->cpage<$pages["l"]){
				$html .= '<li class="pages-list-item" onclick="window.location.href=\'?page='.$pages["n"].'\'">'.$nxt.'</li>';
			}
			if($end){
				$html .= '<li class="pages-list-item" onclick="window.location.href=\'?page='.$pages["l"].'\'">'.$end.'</li>';
			}
		}
		return $html;
	}
	
	public function writeList($pre = 'Prev', $nxt = 'Next', $stt = NULL, $end = NULL){
		echo $this->getList($pre, $nxt, $stt, $end);
	}
	
	private static $config = [
		'CURR'		=>	1,
		'COUNT'		=>	0,
		'PRE'		=>	10,
		'DBTABLE'	=>	NULL,
		'WHERE'		=>	1
	];
	
	public static function config($key, $val=NULL){
		if(is_string($key)&&isset(self::$config[$key])&&$val){
			self::$config[$key]=$val;
		}
		if(is_array($key)){
			foreach($key as $index=>$val){
				self::config($index, $val);
			}
		}
	}
	
	public static function getPageListDataByCount($num=9){
		$paging = new Pager(self::$config["CURR"], $num);
		$paging->setter(self::$config["COUNT"], self::$config["PRE"]);
		$return = $paging->getData();
		unset($paging);
		return $return;
	}
	
	public static function getPageListDataByTable($num=9){
		if(self::$config["DBTABLE"]){
			$paging = new Pager(self::$config["CURR"], $num);
			$paging->query(self::$config["DBTABLE"], self::$config["WHERE"], self::$config["PRE"]);
			$return = $paging->getData();
			unset($paging);
			return $return;
		}
		return [];
	}
}