<?php
namespace TC\Models\SheetsViews;

use Tangram\NIDO\DataObject;
use Model;
use CM\SPC\Preset;
use CM\SPC\Category;
use CM\SPCLite;

class SPCList extends DataObject {
	use traits;

    private $sort, $dir, $count, $cpage, $prePage = 36;
    
    public function __construct($localdict, $length, $uriarr, $cpage, $sort, $preset_id){
		$this->cpage = $cpage;
		$this->sort = $sort;
		$presetinfo = Preset::id($preset_id);
		$this->build($localdict, $uriarr, $presetinfo);
        $this->dir = '';
        for($i = 3; $i < $length; $i++){
            $this->dir .= $uriarr[$i].'/';
        }
		$this->count = SPCLite::count($presetinfo->alias, NULL, SPCLite::RECYCLED);
		$this->buildPagesList($localdict);
    }
    
    private function build($localdict, $uriarr, $presetinfo){
		$empty = $localdict->emtc;
		$words = $localdict->toArray();
		switch($this->sort){
			case 'nmd':
			$sort_type = SPCLite::TITLE_DESC_GBK;
			break;
			case 'mta':
			$sort_type = SPCLite::MTIME_DESC;
			break;
			case 'mtd':
			$sort_type = SPCLite::MTIME_ASC;
			break;
			default:
			$sort_type = SPCLite::TITLE_ASC_GBK;
		}
		
		$start = ($this->cpage-1) * $this->prePage;
		$contents = SPCLite::getList($presetinfo->alias, NULL, SPCLite::RECYCLED, $sort_type, $start, $this->prePage, Model::LIST_AS_ARR);
		$list = '<section class="list-table">';
		if(count($contents)>0){
			$no = 0;
			$list .= $this->getHead($words);
			foreach($contents as $row){
				if(++$no%2==1){
					$list .= '<item class="list odd" datatype="spc" itemtype="'.$uriarr[4].'" itemid="'.$row["ID"].'">';
				}else{
					$list .= '<item class="list even" datatype="spc" itemtype="'.$uriarr[4].'" itemid="'.$row["ID"].'">';
				}
				$list .= '<vision class="sele"><el></el></vision>';
				$list .= '<vision class="name set"><el class="icon"></el><el class="titl">'.$row["TITLE"].'</el></vision>';
				$list .= '<vision class="time">'.date('Y-m-d H:i', strtotime($row["KEY_MTIME"])).'</vision>';
				$list .= '<vision class="back"><click href="trigger://RecoverItems" args="spc, '.$uriarr[4].', '.$row["ID"].'">'.$words["back"].'</click></vision>';
				$list .= '<vision class="dele"><click href="trigger://DeleteItems" args="spc, '.$uriarr[4].', '.$row["ID"].'">'.$words["dele"].'</click></vision>';
				$list .= '</item>';
			}
		}else{
			$list .= '<vision class="tips">'.$empty.'</vision>';
		}
        $list .= '</section>';
		$this->data[] = $list;
	}
    
    public function render(){
        return implode('', $this->data);
    }
}