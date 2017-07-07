<?php
namespace Studio\Stk\Models\ListPageModels;

use Tangram\NIDO\DataObject;
use Model;
use CMF\Models\EMC;
use AF\Util\OIML;
use Library\ect\Pager;

class Sheet extends DataObject {
    public function __construct($localdict, $base){
		$this->data = [];
		$this->listing($localdict, $base);
		$this->paging($localdict, $base);
	}
	
	private function listing($localdict, $base){
		$localdict = $localdict->list;

        switch($base->sort){
            case 'na':
                $sort_order = EMC::NAME_ASC;
                break;
            case 'nd':
                $sort_order = EMC::NAME_DESC;
                break;
            case 'ca':
                $sort_order = EMC::ID_ASC;
                break;
            case 'ma':
                $sort_order = EMC::MTIME_ASC;
                break;
            case 'md':
                $sort_order = EMC::MTIME_DESC;
                break;
            default:
                $sort_order = EMC::ID_DESC;
        }
		$require = [
			'KEY_IS_RECYCLED' => 0
		];
		if($base->type>0&&$base->type<6){
			$require["type"] = $base->type;
		}
		if($base->group||$base->group===''){
			$require["groupname"] = $base->group;
		}

		$base->count = EMC::count($require);
        $maxPage = ceil($this->count / $base->prepage);
        $maxPage = $maxPage ? $maxPage : 1;
        $base->curpage = $base->curpage < 1 ? 1 : $base->curpage;
        $base->curpage = $base->curpage > $maxPage ? $maxPage : $base->curpage;
        $array = EMC::query($require, $sort_order, [($base->curpage-1) * $base->prepage, $base->prepage], Model::LIST_AS_ARR);
		if(count($array)>0){
            $sheet = [
                'head'	=>	[
					'group'		=>	'Group',
					'title'		=>	'Elememt Name',
					'label'		=>	'Label',
					'mtime'		=>	'Modify Time',
					'rmove w70'	=>	'Remove'
				],
				'rows'	=>	[]
			];
            foreach($array as $row){
                $args = $row["id"].', '.$base->sort.', '.$base->curpage.', '.$base->type;
				if(isset($base->group)){
					$args .= ', '.$base->group;
					$group = '&group='.$base->group;
				}else{
					$group = '';
				}
				$sheet["rows"][] = [
					'group'		=>	mb_substr($row["groupname"], 0, 2, 'utf-8'),
					'title'		=>	'<click href="launch://form/emc/'.$row["id"].'/?sort='.$base->sort.'&type='.$base->type.$group.'&page='.$base->curpage.'">'.$row["name"].'</click>',
					'label'		=>	'<click href="trigger://CopyLabelLabel" args="'.$row["label"].'"  NAME="Click to copy ALIAS">'.$row["label"].'</click>',
					'mtime'		=>	date('Y-m-d H:i', strtotime($row["KEY_MTIME"])),
					'rmove w70'	=>	'<click href="trigger://RemoveItem" args="'.$args.'">Remove</click>'
				];
            }
            $this->data['sheet'] = $sheet;
        }
	}
	
	private function paging($localdict, $base){
		$localdict = $localdict->toArray();
		$paging = new Pager($base->curpage, 9);
        $paging->setter($base->count, $base->prepage);
        $this->data['pages'] = [
	        'names'	=>	[
	    	    'f'	=>	$localdict["fst"],
    	    	'l'	=>	$localdict["lst"],
	    	    'p'	=>	$localdict["pre"],
    	    	'n'	=>	$localdict["nxt"],
        	],
	        'pages'	=>	$paging->getData(),
    	    'curr'	=>	$base->curpage,
        	'path'	=>	$base->dirname.'?sort='.$base->sort.'&type='.$base->type.'&group='.$base->group
        ];
	}

	public function render(){
		if(empty($this->data['sheet'])){
            return '<list type="sheet" class="lightdatered"><v class="tips">This Category Is Empty</v></list>';
        }
		return OIML::sheet($this->data['sheet'], 'lightmagenta') . OIML::paging($this->data['pages'], 'lightmagenta');
	}
}
