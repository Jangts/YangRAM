<?php
namespace Studio\Pub\Models;

use System\NIDO\DataObject;
use Model;
use CM\GEC;
use AF\ViewRenderers\OIML;
use Library\ect\Pager;

class GECListPage extends DataObject {
	private $sort, $group, $dir, $count, $prePage = 16, $curPage = 1;

    public function __construct($localdict, $uriarr, $length){
		$localdict = $localdict->toArray();
		$this->data = [];
		$this->dir = '';
		for($i = 4; $i < $length; $i ++){
			$this->dir .= $uriarr[$i].'/';
		}
        $this->leftSide($localdict['side']);
        $this->mainList($localdict['list']);
        $this->pageList($localdict['list']);
	}

    private function leftSide($localdict){
		$sortings = array(
			'na'	=>	'',
			'nd'	=>	'',
			'ca'	=>	'',
			'cd'	=>	'',
			'ma'	=>	'',
			'md'	=>	'',
			'va'	=>	'',
			'vd'	=>	'',
			'ga'	=>	'',
			'gd'	=>	''
		);
		if(isset($_GET["sort"])&&isset($sortings[$_GET["sort"]])){
			$sortings[$_GET["sort"]] = 'selected="true"';
			$this->sort = $_GET["sort"];
		}else{
			$sortings["cd"] = 'selected="true"';
			$this->sort = 'cd';
		}
		if(isset($_GET["group"])){
			$allgroup = '';
			$ungrouped = $_GET["group"] ? '' : 'selected="true"';
			$this->group = $_GET["group"] ? $_GET["group"] : '';
			$group = '&group='.$this->group;
		}else{
			$allgroup = 'selected="true"';
			$ungrouped = '';
			$this->group = NULL;
			$group = '';
		}
		$data = [];
        
        $items = [];
		foreach($sortings as $key=>$s){
			$items[] = [
                'name'  => $localdict[$key],
				'attrs'	=>	$sortings[$key],
				'href'	=>	$this->dir.'?sort='.$key.$group
			];
		}
		$data[] = [
            'name'      =>  $localdict["st"],
            'opened'    =>  'opened',
            'items'     =>  $items
        ];

        $items = [];
        $items[] = [
            'name'  =>  $localdict["all"],
			'attrs'	=>	$allgroup,
			'href'	=>	$this->dir.'?sort='.$this->sort
		];
		$items[] = [
            'name'  =>  $localdict["ug"],
			'attrs'	=>	$ungrouped,
			'href'	=>	$this->dir.'?sort='.$this->sort.'&group'
		];
		$groups = GEC::groups();
		foreach($groups as $row){
			if($row["GROUPCODE"]==$this->group){
				$attrs = 'selected="true"';
			}else{
				$attrs = '';
			}
			$items[] = [
                'name'  =>	$row["GROUPCODE"],
				'attrs'	=>	$attrs,
				'href'	=>	$this->dir.'?sort='.$this->sort.''.'&group='.$row["GROUPCODE"]
			];
		}
		$data[] = [
            'name'      =>  $localdict["gp"],
            'opened'    =>  'opened',
            'items'     =>  $items
        ];
		$this->data['side'] = $data;
	}
	
	private function mainList($localdict){
		switch($this->sort){
			case 'na':
			$order = GEC::TITLE_ASC_GBK;
			break;
			case 'nd':
			$order = GEC::TITLE_DESC_GBK;
			break;
			case 'ca':
			$order = GEC::ID_ASC;
			break;
			case 'ma':
			$order = GEC::MTIME_ASC;
			break;
			case 'md':
			$order = GEC::MTIME_DESC;
			break;
			case 'va':
			$order = GEC::HIT_ASC;
			break;
			case 'vd':
			$order = GEC::HIT_DESC;
			break;
			case 'ga':
			$order = GEC::GROUP_ASC;
			break;
			case 'gd':
			$order = GEC::GROUP_DESC;
			break;
			default:
			$order = GEC::ID_DESC;
		}
		$status = GEC::UNRECYCLED;
		$this->count = GEC::count($this->group, $status);
		$maxPage = ceil($this->count / $this->prePage);
		$maxPage = $maxPage ? $maxPage : 1;
		if(empty($_GET["page"])){
            $this->curPage = $this->curPage < 1 ? 1 : $this->curPage;
		}else{
            $this->curPage = $this->curPage > $maxPage ? $maxPage : intval($_GET["page"]);
        }   
		$array = GEC::getList($this->group, $order, ($this->curPage-1) * $this->prePage, $this->prePage, $status, Model::LIST_AS_ARR);
		if(count($array)>0){
			$data = array(
				'head'	=>	array(
					'group'		=>	'',
					'title'		=>	$localdict["title"],
					'alias'		=>	$localdict["alias"],
					'count w60'	=>	$localdict["count"],
					'mtime'		=>	$localdict["mtime"],
					'rmove w70'	=>	$localdict["rmove"],
				),
				'rows'	=>	[]
			);
			foreach($array as $row){
				if($this->group){
					$href = 'launch://gec/general/'.$row["ID"].'/?sort='.$this->sort.'&group='.$this->group.'&page='.$this->curPage;
					$args = 'general, '.$row["ID"].', '.$this->sort.', '.$this->curPage.', '.$this->group;
				}else{
					$href = 'launch://gec/general/'.$row["ID"].'/?sort='.$this->sort.'&page='.$this->curPage;
					$args = 'general, '.$row["ID"].', '.$this->sort.', '.$this->curPage;
				}
				$data["rows"][] = array(
					'group'		=>	mb_substr($row["GROUPCODE"], 0, 1, 'utf-8'),
					'title'		=>	'<click href="'.$href.'">'.$row["TITLE"].'</click>',
					'alias'		=>	'<click href="trigger://CopyContentAlias" args="'.$row["ALIAS"].':'.$row["ID"].'"  title="Click to copy ALIAS and ID">'.$row["ALIAS"].'</click>',
					'count w60'	=>	$row["KEY_COUNT"],
					'mtime'		=>	date('Y-m-d H:i', strtotime($row["KEY_MTIME"])),
					'rmove w70'	=>	'<click href="trigger://removeItem" args="'.$args.'">Remove</click>'
				);
			}
			$this->data['list'] = $data;
		}
	}
	
	private function pageList($localdict){
		$paging = new Pager($this->curPage, 9);
		$paging->setter($this->count, $this->prePage);
		if($this->group){
			$path = $this->dir.'?sort='.$this->sort.'&group='.$this->group;
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
				return OIML::sheet($this->data['list'], 'lightdatered');
			}
			return '<list type="sheet" class="lightdatered"><vision class="tips">This Category Is Empty</vision></list>';
		}
		if($posi=='page'){
			return OIML::paging($this->data['page'], 'lightdatered');
		}
		return '';
	}
}
