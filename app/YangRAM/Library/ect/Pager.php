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
					$html .= '<li class="pages-list-item actived" onclick="window.location.href=\'?page='.$pages[$n].'\'">'.$pages[$n].'</li>';
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

	public function renderList($tempate, $options = []){
		$options = array_merge([
			'first'		=> 'First',
			'privious'	=> 'Prev',
			'cpagetag'	=> ' actived',
			'next'		=> 'Next',
			'last'		=> 'Last'
		], $options);
		$pages = $this->getData();
		if($pages["length"] > 0){
			if($options['first']){
				echo str_replace('{@page_cur}', '', str_replace('{@page_num}', $pages["f"], str_replace('{@page_tit}', $options['first'], $tempate)));
			}
			if($this->cpage>$pages["f"]){
				echo str_replace('{@page_cur}', '', str_replace('{@page_num}', $pages["p"], str_replace('{@page_tit}', $options['privious'], $tempate)));
			}
			for ($n = 0; $n < $pages["length"]; $n++) {
				if ($pages[$n] == $this->cpage) {
					echo str_replace('{@page_cur}', $options['cpagetag'], str_replace('{@page_num}',$pages[$n], str_replace('{@page_tit}', $pages[$n], $tempate)));
				}else{
					echo str_replace('{@page_cur}', '', str_replace('{@page_num}',$pages[$n], str_replace('{@page_tit}', $pages[$n], $tempate)));
				}
			}
			if($this->cpage<$pages["l"]){
				echo str_replace('{@page_cur}', '', str_replace('{@page_num}', $pages["n"], str_replace('{@page_tit}', $options['next'], $tempate)));
			}
			if($options['last']){
				echo str_replace('{@page_cur}', '', str_replace('{@page_num}', $pages["l"], str_replace('{@page_tit}', $options['last'], $tempate)));
			}
		}
		return 1;
	}
	
	public function writeList($pre = 'Prev', $nxt = 'Next', $stt = NULL, $end = NULL){
		echo $this->getList($pre, $nxt, $stt, $end);
	}
	
	private static $config = [
		'CPAGE'		=>	1,
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
	
	public static function getPageListDataByCount($num=9, $cpage = false){
		$paging = new Pager(is_numeric($cpage)? $cpage : self::$config["CPAGE"], $num);
		$paging->setter(self::$config["COUNT"], self::$config["PRE"]);
		$return = $paging->getData();
		unset($paging);
		return $return;
	}

	public static function renderPageList($options = [], $tempate = '<li class="pages-list-item{@page_cur}"><a href="?page={@page_num}">{@page_tit}</a></li>'){
		$options = array_merge([
			'itemnum'	=>	9,
			'first'		=> 'First',
			'privious'	=> 'Prev',
			'cpagetag'	=> ' actived',
			'cpagenum'		=>	self::$config["CPAGE"],
			'next'		=> 'Next',
			'last'		=> 'Last'
		], $options);
		$paging = new Pager($options['cpagenum'], $options['itemnum']);
		$paging->setter(self::$config["COUNT"], self::$config["PRE"]);
		$paging->renderList($tempate, $options);
		return 1;
	}
	
	public static function getPageListDataByTable($num=9, $cpage = false){
		if(self::$config["DBTABLE"]){
			$paging = new Pager(is_numeric($cpage)? $cpage : self::$config["CPAGE"], $num);
			$paging->query(self::$config["DBTABLE"], self::$config["WHERE"], self::$config["PRE"]);
			$return = $paging->getData();
			unset($paging);
			return $return;
		}
		return [];
	}
}