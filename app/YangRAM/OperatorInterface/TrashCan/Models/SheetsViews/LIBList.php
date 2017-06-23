<?php
namespace TC\Models\SheetsViews;

use Status;
use Tangram\NIDO\DataObject;
use Model;
use CM\GEC;
use CM\EMC;
use CM\SRC;
use CM\SRCLite;
use CM\SRC\Folder;

class LIBList extends DataObject {
	use traits;

	private static
	$sorts = ['nma', 'nmd', 'mta', 'mtd'],
	$types = ['img', 'wav', 'doc', 'fld', 'txt', 'vod', 'gec'];

    private $sort, $dir, $count, $cpage, $prePage = 36;
	
    public function __construct($localdict, $length, $uriarr, $cpage, $sort, $type){
		$this->cpage = $cpage;
		$this->sort = $sort;
		switch($type){
			case 'img': case 'txt': case 'wav': case 'vod': case 'doc':
			$this->getFiles($localdict, $type);
			break;
			case 'gec':
			$this->getGEContents($localdict);
			$this->count = GEC::count(NULL, GEC::RECYCLED);
			break;
			case 'emc':
			$this->getEMContents($localdict);
			$this->count = EMC::count("`KEY_IS_RECYCLED` = 1");
			break;
			case 'fld':
			$this->getFolders($localdict);
			break;
			default:
			new Status(404, __URI, true);
		}
		$this->dir = '';
        for($i = 3; $i < $length; $i++){
            $this->dir .= $uriarr[$i].'/';
        }
		$this->buildPagesList($localdict);
	}
		
	private function getFiles($localdict, $type){
		switch($this->sort){
			case 'nmd':
			$sort_type = SRCLite::FNAME_DESC_GBK;
			break;
			case 'mta':
			$sort_type = SRCLite::MTIME_ASC;
			break;
			case 'mtd':
			$sort_type = SRCLite::MTIME_DESC;
			break;
			default:
			$sort_type = SRCLite::FNAME_ASC_GBK;
		}
		$start = ($this->cpage - 1) * $this->prePage;
		$files = SRCLite::query($type, "`KEY_IS_RECYCLED` = 1", $sort_type);
		$array = array_slice($files, $start, $this->prePage);
		switch($type){
			case 'img':
			$this->getPictures($localdict, $array);
			break;
			case 'txt':
			case 'wav':
			case 'vod':
			$this->getMediasAndTexts($localdict, $type, $array);
			break;
			case 'doc':
			$this->getDocuments($localdict, $array);
			break;
		}
		$this->count = count($files);
	}
	
	private function getPictures($localdict, $array){
		$empty = $localdict->emtc;
		$words = $localdict->toArray();
		$list = '<section class="img-list">';
		if(count($array)>0){
			foreach($array as $row){
				$row = $row->extend();
				if($row){
					$ratio = $row->HEIGHT/$row->WIDTH;
					if($ratio <= 7.5){
					$src = __GET_DIR.'files/img/'.$row->ID.'.'.$row->SUFFIX.'_80.'.$row->SUFFIX;
					}else{
						$width = 60 / $ratio;
						$src = __GET_DIR.'files/img/'.$row->ID.'.'.$row->SUFFIX.'_'.$width.'x60.'.$row->SUFFIX;
					}
					$list .= '<item class="img-item" datatype="lib" itemtype="img" itemid="'.$row->ID.'">';
					$list .= '<vision class="sele"><el></el></vision>';
					$list .= '<vision class="img-left"><img src="'.$src.'"></vision>';
					$list .= '<vision class="img-right"><p>'.$row->FILE_NAME.'</p>';
					$list .= '<p>'.$row->WIDTH.'px×'.$row->WIDTH.'px</p>';
					$list .= '<p>'.date('Y-m-d H:i', strtotime($row->KEY_MTIME)).'</p></vision>';
					$list .= '<vision class="img-btns">';
					$list .= '<click href="trigger://RecoverItems" args="lib, img, '.$row->ID.'">'.$words["back"].'</click>';
					$list .= '<click href="trigger://DeleteItems" args="lib, img, '.$row->ID.'">'.$words["dele"].'</click>';
					$list .= '</vision></item>';
				}
			}
		}else{
			$list .= '<vision class="tips">'.$empty.'</vision>';
		}
        $list .= '</section>';
		$this->data[] = $list;
	}
	
	private function getMediasAndTexts($localdict, $type, $array){
		$empty = $localdict->emtc;
		$words = $localdict->toArray();
		$list = '<section class="list-table">';
		if(count($array)>0){
			$no = 0;
			$list .= $this->getHead($words);
			foreach($array as $row){
				if(++$no%2==1){
					$list .= '<item class="list odd" datatype="lib" itemtype="'.$type.'" itemid="'.$row->ID.'">';
				}else{
					$list .= '<item class="list even" datatype="lib" itemtype="'.$type.'" itemid="'.$row->ID.'">';
				}
				$list .= '<vision class="sele"><el></el></vision>';
				$list .= '<vision class="name '.$type.'"><el class="icon"></el><el class="titl">'.$row->FILE_NAME.'</el></vision>';
				$list .= '<vision class="time">'.date('Y-m-d H:i', strtotime($row->KEY_MTIME)).'</vision>';
				$list .= '<vision class="back"><click href="trigger://RecoverItems" args="lib, '.$type.', '.$row->ID.'">'.$words["back"].'</click></vision>';
				$list .= '<vision class="dele"><click href="trigger://DeleteItems" args="lib, '.$type.', '.$row->ID.'">'.$words["dele"].'</click></vision>';
				$list .= '</item>';
			}
		}else{
			$list .= '<vision class="tips">'.$empty.'</vision>';
		}
        $list .= '</section>';
		$this->data[] = $list;
	}
	
	private function getDocuments($localdict, $array){
		$empty = $localdict->emtc;
		$words = $localdict->toArray();
		$list = '<section class="list-table">';
		if(count($array)>0){
			$no = 0;
			$list .= $this->getHead($words);
			foreach($array as $row){
				if(++$no%2==1){
					$list .= '<item class="list odd" datatype="lib" itemtype="doc" itemid="'.$row->ID.'">';
				}else{
					$list .= '<item class="list even" datatype="lib" itemtype="doc" itemid="'.$row->ID.'">';
				}
				$list .= '<vision class="sele"><el></el></vision>';
				$list .= '<vision class="name '.$row->SUFFIX.'">';
				if(preg_match('/(rar|zip|xls|doc|ppt|xlsx|docx|pptx|pdf|txt)/', $row->SUFFIX, $matchs)){
					$list .= '<el class="icon"></el>';
				}else{
					$list .= '<el class="sfix">['.strtoupper($row->SUFFIX).']</el>';
				}
				$list .= '<el class="titl">'.$row->FILE_NAME.'</el></vision>';
				$list .= '<vision class="time">'.date('Y-m-d H:i', strtotime($row->KEY_MTIME)).'</vision>';
				$list .= '<vision class="back"><click href="trigger://RecoverItems" args="lib, doc, '.$row->ID.'">'.$words["back"].'</click></vision>';
				$list .= '<vision class="dele"><click href="trigger://DeleteItems" args="lib, doc, '.$row->ID.'">'.$words["dele"].'</click></vision>';
				$list .= '</item>';
			}
		}else{
			$list .= '<vision class="tips">'.$empty.'</vision>';
		}
        $list .= '</section>';
		$this->data[] = $list;
	}
	
	private function getGEContents($localdict){
		$empty = $localdict->emtc;
		$words = $localdict->toArray();
		switch($this->sort){
			case 'nmd':
			$sort_type = GEC::TITLE_DESC_GBK;
			break;
			case 'mta':
			$sort_type = GEC::MTIME_DESC;
			break;
			case 'mtd':
			$sort_type = GEC::MTIME_ASC;
			break;
			default:
			$sort_type = GEC::TITLE_ASC_GBK;
		}
		$start = ($this->cpage-1) * $this->prePage;
		$contents = GEC::getList(NULL, $sort_type, $start, $this->prePage, GEC::RECYCLED);
		$array = array_slice($contents, $start, $this->prePage);
		$list = '<section class="list-table">';
		if(count($array)>0){
			$no = 0;
			$list .= $this->getHead($words);
			foreach($array as $row){
				if(++$no%2==1){
					$list .= '<item class="list odd" datatype="lib" itemtype="gec" itemid="'.$row->ID.'">';
				}else{
					$list .= '<item class="list even" datatype="lib" itemtype="gec" itemid="'.$row->ID.'">';
				}
				$list .= '<vision class="sele"><el></el></vision>';
				$list .= '<vision class="name gec"><el class="icon"></el><el class="titl">'.$row->TITLE.'</el></vision>';
				$list .= '<vision class="time">'.date('Y-m-d H:i', strtotime($row->KEY_MTIME)).'</vision>';
				$list .= '<vision class="back"><click href="trigger://RecoverItems" args="lib, gec, '.$row->ID.'">'.$words["back"].'</click></vision>';
				$list .= '<vision class="dele"><click href="trigger://DeleteItems" args="lib, gec, '.$row->ID.'">'.$words["dele"].'</click></vision>';
				$list .= '</item>';
			}
		}else{
			$list .= '<vision class="tips">'.$empty.'</vision>';
		}
        $list .= '</section>';
		$this->data[] = $list;
		$this->count = count($contents);
	}

	private function getEMContents($localdict){
		$empty = $localdict->emtc;
		$words = $localdict->toArray();
		switch($this->sort){
			case 'nmd':
			$sort_type = EMC::NAME_DESC_GBK;
			break;
			case 'mta':
			$sort_type = EMC::MTIME_DESC;
			break;
			case 'mtd':
			$sort_type = EMC::MTIME_ASC;
			break;
			default:
			$sort_type = EMC::NAME_ASC_GBK;
		}
		$start = ($this->cpage-1) * $this->prePage;
		$contents = EMC::query ("`KEY_IS_RECYCLED` = 1" , $sort_type);
		$array = array_slice($contents, $start, $this->prePage);
		$list = '<section class="list-table">';
		if(count($array)>0){
			$no = 0;
			$list .= $this->getHead($words);
			foreach($array as $row){
				if(++$no%2==1){
					$list .= '<item class="list odd" datatype="lib" itemtype="emc" itemid="'.$row->id.'">';
				}else{
					$list .= '<item class="list even" datatype="lib" itemtype="emc" itemid="'.$row->id.'">';
				}
				$list .= '<vision class="sele"><el></el></vision>';
				$list .= '<vision class="name eac"><el class="icon"></el><el class="titl">'.$row->name.'</el></vision>';
				$list .= '<vision class="time">'.date('Y-m-d H:i', strtotime($row->KEY_MTIME)).'</vision>';
				$list .= '<vision class="back"><click href="trigger://RecoverItems" args="lib, emc, '.$row->id.'">'.$words["back"].'</click></vision>';
				$list .= '<vision class="dele"><click href="trigger://DeleteItems" args="lib, emc, '.$row->id.'">'.$words["dele"].'</click></vision>';
				$list .= '</item>';
			}
		}else{
			$list .= '<vision class="tips">'.$empty.'</vision>';
		}
        $list .= '</section>';
		$this->data[] = $list;
		$this->count = count($contents);
	}
	
	private function getFolders($localdict){
		$empty = $localdict->emtc;
		$words = $localdict->toArray();
		switch($this->sort){
			case 'nmd':
			$sort_type = Folder::NAME_DESC_GBK;
			break;
			case 'mta':
			$sort_type = Folder::MTIME_ASC;
			break;
			case 'mtd':
			$sort_type = Folder::MTIME_DESC;
			break;
			default:
			$sort_type = Folder::NAME_ASC_GBK;
		}
		$folders = Folder::query("`KEY_IS_RECYCLED` = 1", $sort_type);
		$array = array_slice($folders, $start, $this->prePage);
		$list = '<section class="list-table">';
		if(count($array)>0){
			$no = 0;
			$list .= $this->getHead($words);
			foreach($array as $row){
				if(++$no%2==1){
					$list .= '<item class="list odd" datatype="lib" itemtype="fld" itemid="'.$row->id.'">';
				}else{
					$list .= '<item class="list even" datatype="lib" itemtype="fld" itemid="'.$row->id.'">';
				}
				$list .= '<vision class="sele"><el></el></vision>';
				$list .= '<vision class="name fld"><el class="icon"></el><el class="titl">'.$row->name.'</el></vision>';
				$list .= '<vision class="time">'.date('Y-m-d H:i', strtotime($row->KEY_MTIME)).'</vision>';
				$list .= '<vision class="back"><click href="trigger://RecoverItems" args="lib, fld, '.$row->id.'">'.$words["back"].'</click></vision>';
				$list .= '<vision class="dele"><click href="trigger://DeleteItems" args="lib, fld, '.$row->id.'">'.$words["dele"].'</click></vision>';
				$list .= '</item>';
			}
		}else{
			$list .= '<vision class="tips">'.$empty.'</vision>';
		}
        $list .= '</section>';
		$this->data[] = $list;
		$this->count = count($folders);
	}

	public function render(){
        return implode('', $this->data);
    }
}
