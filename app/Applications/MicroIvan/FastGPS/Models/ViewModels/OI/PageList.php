<?php
namespace GPS\Models\ViewModels\OI;

use Status;
use Tangram\NIDO\DataObject;
use Model;
use AF\ViewRenderers\OIML;
use Library\ect\Pager;
use GPS\Models\Data\Page;



class PageList extends DataObject {
	private static
	$sort_orders = [
		'na' => Page::NAME_ASC_GBK,
		'nd' => Page::NAME_DESC_GBK,
		'ca' => Page::PID_ASC,
		'cd' => Page::PID_DESC,
		'ma' => Page::MTIME_ASC,
		'md' => Page::MTIME_DESC,
		'va' => Page::VC_ASC,
		'vd' => Page::VC_DESC
	],
	$page_status = array(
		'all'	=>	Page::ALL,
		'use'	=>	Page::INUSE,
		'unu'	=>	Page::UNUSE,
	);

	private
	$dir,
	$sort,
	$sortings = array(
		'na'	=>	'',
		'nd'	=>	'',
		'ca'	=>	'',
		'cd'	=>	'',
		'ma'	=>	'',
		'md'	=>	'',
		'va'	=>	'',
		'vd'	=>	''
	),
	$stts,
	$status = array(
		'all'	=>	'',
		'use'	=>	'',
		'unu'	=>	'',
	),	
	$type, $count,
	$prePage = 16,
	$curPage = 1;

    public function __construct($type, $localdict, $uriarr, $length, $params){
		$this->type = $type;
		$localdict = $localdict->toArray();
		$this->data = [];
		$this->dir = '';
		for($i = 4; $i < $length; $i ++){
			$this->dir .= $uriarr[$i].'/';
		}
		$this->checkParams($params->toArray());
        $this->leftSide($localdict);
        $this->mainList($localdict);
        $this->pageList($localdict);
	}

	private function checkParams($params){
		$sortings = $this->sortings;
		$status = $this->status;
		if(isset($params["sort"])&&isset($sortings[$params["sort"]])){
			$this->sortings[$params["sort"]] = 'selected="true"';
			$this->sort = $params["sort"];
		}else{
			$this->sortings["cd"] = 'selected="true"';
			$this->sort = 'cd';
		}
		if(isset($params["stts"])&&isset($status[$params["stts"]])){
			$this->status[$params["stts"]] = 'selected';
			$this->stts = $params["stts"];
		}else{
			$this->status["all"] = 'selected';
			$this->stts = 'all';
		}
	}

    private function leftSide($localdict){
		$data = [];
        $items = [];
		foreach($this->sortings as $key=>$s){
			$items[] = [
                'name'  => $localdict[$key],
				'attrs'	=>	$s,
				'href'	=>	$this->dir.'?sort='.$key.'&stts='.$this->stts
			];
		}
		$data[] = [
            'name'      =>  $localdict["st"],
            'opened'    =>  'opened',
            'items'     =>  $items
        ];

		$items = [];
		foreach($this->status as $key=>$s){
			$items[] = [
                'name'  => $localdict[$key],
				'attrs'	=>	$s,
				'href'	=>	$this->dir.'?sort='.$this->sort.'&stts='.$key
			];
		}
		$data[] = [
            'name'      =>  $localdict["cdt"],
            'opened'    =>  'opened',
            'items'     =>  $items
        ];
		$this->data['side'] = $data;
	}
	
	private function mainList($localdict){
		$sort_order = self::$sort_orders[$this->sort];
		$status = self::$page_status[$this->stts];

		$this->count = Page::count($this->type, $status);
		$maxPage = ceil($this->count / $this->prePage);
		$maxPage = $maxPage ? $maxPage : 1;
		if(empty($params["page"])){
            $this->curPage = $this->curPage < 1 ? 1 : $this->curPage;
		}else{
            $this->curPage = $this->curPage > $maxPage ? $maxPage : intval($params["page"]);
        }   
		$array = Page::getList($this->type, $sort_order, $status, ($this->curPage-1) * $this->prePage, $this->prePage, Model::LIST_AS_ARR);
		//var_dump($array);
		//die;
		if(count($array)>0){
			$data = array(
				'head'	=>	array(
					'stts w80'	=>	$localdict['attrs']["stts"],
					'name'		=>	$localdict['attrs']["name"],
					'hits w60'	=>	$localdict['attrs']["hits"],
					'time w140'	=>	$localdict['attrs']["time"],
					'modi w70'	=>	$localdict['attrs']["modi"],
					'dele w70'	=>	$localdict['attrs']["dele"]
				),
				'rows'	=>	[]
			);
			foreach($array as $row){
				if($row["KEY_STATE"]==1){
					$status = '<el class="pubed"><click href="trigger://'.AI_CURR.'::NonUse" args="'.$row["pid"].'">'.$localdict["use"].'</el>';
				}else{
					$status = '<el class="unpub"><click href="trigger://'.AI_CURR.'::StartUse" args="'.$row["pid"].'">'.$localdict["unu"].'</el>';
				}
				$data["rows"][] = array(
					'stts w80'	=>	$status,
					'name'		=>	'<click href="trigger://'.AI_CURR.'::EditItem" args="'.$row["pid"].'">'.$row["name"].'</click>',
					'hits w60'	=>	$row["KEY_COUNT"],
					'time w140'	=>	date('Y-m-d H:i', strtotime($row["KEY_MTIME"])),
					'modi w70'	=>	'<click href="trigger://'.AI_CURR.'::CopyMark" args="'.$row["mark"].'">'.$localdict['attrs']["modi"].'</click>',
					'dele w70'	=>	'<click href="trigger://'.AI_CURR.'::RemoveItem" args="'.$row["pid"].'">'.$localdict['attrs']["dele"].'</click>'
				);
			}
			$this->data['list'] = $data;
		}
	}
	
	private function pageList($localdict){
		$paging = new Pager($this->curPage, 9);
		$paging->setter($this->count, $this->prePage);
		if($this->type){
			$path = $this->dir.'?sort='.$this->sort.'&group='.$this->type;
		}else{
			$path = $this->dir.'?sort='.$this->sort;
		}
		$data = array(
			'names'	=>	array(
				'f'	=>	$localdict["fst"],
				'l'	=>	$localdict["lst"],
				'p'	=>	$localdict["pre"],
				'n'	=>	$localdict["nxt"],
			),
			'pages'	=>	$paging->getData(),
			'curr'	=>	$this->curPage,
			'path'	=>	$path
		);
		$this->data['page'] = $data;
	}

	public function render($posi){
		if($posi=='side'){
			return OIML::menu($this->data['side']);
		}
		if($posi=='list'){
			if(isset($this->data['list'])){
				return OIML::sheet($this->data['list'], 'dark');
			}
			return '<list type="sheet" class="dark"><vision class="tips">This Category Is Empty</vision></list>';
		}
		if($posi=='page'){
			return OIML::paging($this->data['page'], 'dark');
		}
		return '';
	}
}