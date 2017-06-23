<?php
namespace Studio\Pub\Models\SPCListPage;

use Tangram\NIDO\DataObject;
use CM\SPCLite;
use AF\ViewRenderers\OIML;

class Sheet extends DataObject {
    
    public function __construct($localdict, $presetinfo, $base){
        $localdict = $localdict->list;
        $presetalias = $presetinfo->alias;

        switch($base->sort){
            case 'na':
                $sort_order = SPCLite::TITLE_ASC;
                break;
            case 'nd':
                $sort_order = SPCLite::TITLE_DESC;
                break;
            case 'ca':
                $sort_order = SPCLite::ID_ASC;
                break;
            case 'ma':
                $sort_order = SPCLite::MTIME_ASC;
                break;
            case 'md':
                $sort_order = SPCLite::MTIME_DESC;
                break;
            case 'va':
                $sort_order = SPCLite::HIT_ASC;
                break;
            case 'vd':
                $sort_order = SPCLite::HIT_DESC;
                break;
            default:
                $sort_order = SPCLite::ID_DESC;
        }
        
        switch($base->stts){
            case 'pub':
                $status = SPCLite::PUBLISHED;
                break;
            case 'unp':
                $status = SPCLite::UNPUBLISHED;
                break;
            case 'pos':
                $status = SPCLite::POSTED;
                break;
            case 'top':
                $status = SPCLite::ISTOP;
                break;
            default:
                $status = SPCLite::UNRECYCLED;
        }
        $base->count = SPCLite::count($presetalias, $base->category, $status);
        $maxPage = ceil($base->count / $base->prepage);
        $maxPage = $maxPage ? $maxPage : 1;
        $base->cpage = $base->cpage < 1 ? 1 : $base->cpage;
        $base->cpage = $base->cpage > $maxPage ? $maxPage : $base->cpage;
        $array = SPCLite::getList($presetalias, $base->category, $status, $sort_order, ($base->cpage-1) * $base->prepage, $base->prepage);
        if(count($array)>0){
            $data = [
                'head'	=>	[
                    'stts w80'	=>	$localdict["stts"],
                    'name'		=>	$localdict["name"],
                    'hits w60'	=>	$localdict["hits"],
                    'time'		=>	$localdict["time"],
                    'modi w70'	=>	$localdict["modi"],
                    'dele w70'	=>	$localdict["dele"],
                ],
                'rows'	=>	[]
            ];
            foreach($array as $row){
                $row = $row->toArray();
                if($row["KEY_STATE"]==1){
                    $status = '<el class="pubed">'.$localdict['pub'].'</el>';
                }else{
                    $status = '<el class="unpub">'.$localdict['unp'].'</el>';
                }
                $data["rows"][] = [
                'stts w80'	=>	$status,
                'name'		=>	'<click href="launch://'.AI_CURR.'::spc/'.$presetalias.'/'.$row["ID"].'/?sort='.$base->sort.'&stts='.$base->stts.'&cat='.$base->category.'&page='.$base->cpage.'">'.$row["TITLE"].'</click>',
                'hits w60'	=>	$row["KEY_COUNT"],
                'time'		=>	date('Y-m-d H:i', strtotime($row["KEY_MTIME"])),
                'modi w70'	=>	'<click href="launch://'.AI_CURR.'::spc/'.$presetalias.'/'.$row["ID"].'/?sort='.$base->sort.'&stts='.$base->stts.'&cat='.$base->category.'&page='.$base->cpage.'">'.$localdict["modi"].'</click>',
                'dele w70'	=>	'<click href="trigger://'.AI_CURR.'::removeItem" args="'.$presetalias.', '.$row["ID"].', '.$base->sort.', '.$base->cpage.', '.$base->stts.', '.$base->category.'">'.$localdict["dele"].'</click>'
                ];
            }
            $this->data = $data;
        }
    }

    public function render(){
        if(count($this->data)){
            return OIML::sheet($this->data, 'lightdatered');
        }
        return '<list type="sheet" class="lightdatered"><vision class="tips">This Category Is Empty</vision></list>';
    }
}