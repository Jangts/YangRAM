<?php
namespace Explorer\Models\ViewModels;
use System\NIDO\DataObject;
use Model;
use RDO;
use CM\SPC\Preset;
use CM\SPCLite;

class SPC extends DataObject {
    use traits\spc_writer;

	public function __construct($localdict, $presetid, $month){
        $localdict = $localdict->toArray();
        if($month){
			if(isset($_GET["lo"])){
                switch($_GET["lo"]){
                    case 'nd':
                        $orderby = SPCLite::ID_ASC;
                        break;
                    case 'ta':
                        $orderby = SPCLite::ID_ASC;
                        break;
                    case 'td':
                        $orderby = SPCLite::ID_ASC;
                        break;
                    default:
                        $orderby = SPCLite::ID_ASC;
                }
            }else{
                $orderby = SPCLite::ID_ASC;
            }
            $this->getContents($localdict, $presetid, $month, $orderby);
        }else{
            $this->getMonths($localdict, $presetid);
        }
	}

	private function getContents($localdict, $presetid, $month, $orderby){
        $path = 'spc/preset/'.$presetid.'/'.$month.'/';
        $presetinfo = Preset::id($presetid);
        $preset = $presetinfo->alias;
		$array = SPCLite::query ("`SET_ALIAS` = '".$preset."' AND DATE_FORMAT(PUBTIME, '%Y-%m') = '$month' AND `KEY_IS_RECYCLED` = 0" , $orderby, 0, Model::LIST_AS_ARR);
        if(count($array)){
            foreach($array as $row){
                $this->writeContents($localdict, $preset, $presetinfo, $row);
            }
        }
    }

	private function getMonths($localdict, $presetid){
        $path = 'spc/preset/'.$presetid.'/';
        $presetinfo = Preset::id($presetid);
        $rdo = new RDO;
        $tsr = $rdo->using(DB_CNT.'in_special_use')
            ->requiring("SET_ALIAS = '$presetinfo->alias'")
            ->select('DISTINCT DATE_FORMAT(PUBTIME, "%Y-%m") AS month', false, false);
        if($tsr){
            $array = $tsr->toArray();
            foreach($array as $row){
                $href = $path.$row["month"].'/';
                $this->writeMonths($localdict, $row, $href);
            }
        }
    }

	public function render($localdict){
        if(count($this->data)){
			return join('', $this->data);
		}else{
			return '<el>'.$localdict->empty.'</el>';
		}
	}
}
