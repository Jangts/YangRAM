<?php
namespace TC\Models\SheetsViews;

use RDO;
use Tangram\NIDO\DataObject;
use TC\Models\Data\RecycleRule;

class XTDList extends DataObject {
	use traits;

    private $sort, $dir, $count, $cpage, $prePage = 36;
    
    public function __construct($localdict, $length, $uriarr, $cpage, $sort, $rule_id){
		$this->cpage = $cpage;
		$this->sort = $sort;
		$rule = RecycleRule::identity($rule_id);
		$this->build($localdict, $uriarr, $rule->toArray());
        $this->dir = '';
        for($i = 3; $i < $length; $i++){
            $this->dir .= $uriarr[$i].'/';
        }
		$this->buildPagesList($localdict);
    }

	private function build($localdict, $uriarr, $rule){
		$empty = $localdict->emtc;
		$words = $localdict->toArray();
		switch($this->sort){
			case 'nmd':
			$sort_type = ['CONVERT('.$rule["title_field"].' USING gbk)', true];
			break;
			case 'mta':
			$sort_type = [$rule["recycled_time_field"], false];
			break;
			case 'mtd':
			$sort_type = [$rule["recycled_time_field"], true];
			break;
			default:
			$sort_type = ['CONVERT('.$rule["title_field"].' USING gbk)', false];
		}

		$rdo = new RDO;
		$rdos = $rdo->using(DB_APP.$rule["handle_appid"].'_'.$rule["database_table"])
		->where($rule["recycled_state_field"], 1)
		->orderby($sort_type[0], $sort_type[1])
		->select();

		$array = $rdos->toArray();
		$this->count = $rdos->getCount();
		$list = '<section class="list-table">';
		if($this->count){
			$no = 0;
			$list .= $this->getHead($words);
			foreach($array as $row){
				if(++$no%2==1){
					$list .= '<item class="list odd" datatype="xtd" itemtype="'.$rule["id"].'" itemid="'.$row[$rule["index_field"]].'">';
				}else{
					$list .= '<item class="list even" datatype="xtd" itemtype="'.$rule["id"].'" itemid="'.$row[$rule["index_field"]].'">';
				}
				$list .= '<vision class="sele"><el></el></vision>';
				$list .= '<vision class="name set"><el class="icon"></el><el class="titl">'.$row[$rule["title_field"]].'</el></vision>';
				$list .= '<vision class="time">'.date('Y-m-d H:i', strtotime($row[$rule["recycled_time_field"]])).'</vision>';
				$list .= '<vision class="back"><click href="trigger://RecoverItems" args="xtd, '.$rule["id"].', '.$row[$rule["index_field"]].'">'.$words["back"].'</click></vision>';
				$list .= '<vision class="dele"><click href="trigger://DeleteItems" args="xtd, '.$rule["id"].', '.$row[$rule["index_field"]].'">'.$words["dele"].'</click></vision>';
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