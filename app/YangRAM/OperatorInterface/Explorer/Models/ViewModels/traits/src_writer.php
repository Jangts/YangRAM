<?php
namespace Explorer\Models\ViewModels\traits;

use CMF\Models\SRC;
use Library\formattings\ScalarFormat;

trait src_writer {
    private function writeFolders($localdict, $row, $href, $readonly = ''){
		$block = '<block name="'.$row["name"].'" title="'.$row["name"].'" x-type="folder" menu="ctx-fld" class="item folder" x-href="'.$href.'" x-id="'.$row["id"].'" '.$readonly.'>';
	    $block .= '<v class="sele"></v>';
		$block .= '<v class="rplc"></v>';
		if($row["id"]<=6){
			$block .= '<v class="icon"></v><v class="name"  x-column="'.$localdict["attrs"]["name"].'">'.$localdict["folders"][$row["id"]].'</v>';
		}else{
			$block .= '<v class="icon"></v><v class="name" x-column="'.$localdict["attrs"]["name"].'">'.$row["name"].'</v>';
		}
		$block .= '<v class="none" x-column="-">-</v>';
	    $block .= '<v class="size" x-column="'.$localdict["attrs"]["size"].'">-</v>';
	    $block .= '<v class="time" x-column="'.$localdict["attrs"]["time"].'">'.$row["KEY_MTIME"].'</v></block>';
		$this->data[] = $block;
	}

    private function writePictures($localdict, $row, $readonly = ''){
		$extend = SRC::getSource($row['SRC_ID'], 'img');
		if($extend){
			$row = array_merge($row, $extend->toArray());
			$ratio = $row["HEIGHT"]/$row["WIDTH"];
			$mt = strtotime($row["KEY_MTIME"]);
			if($ratio <= 7.5){
				$src = __GET_DIR.'files/img/'.$row["ID"].'.'.$row["SUFFIX"].'_120.'.$row["SUFFIX"].'?mt='.$mt;
			}else{
				$width = 90 / $ratio;
				$src = __GET_DIR.'files/img/'.$row["ID"].'.'.$row["SUFFIX"].'_'.$width.'x90.'.$row["SUFFIX"].'?mt='.$mt;
			}
			$block = '<block name="'.$row["FILE_NAME"].'" title="'.$row["FILE_NAME"].'" x-type="img" menu="ctx-img" class="item img" x-id="'.$row["ID"].'" x-suffix="'.$row["SUFFIX"].'" '.$readonly.'>';
			$block .= '<v class="sele"></v>';
			$block .= '<v class="rplc"></v>';
			$block .= '<v class="icon" style="background-image: url('.$src.');"></v>';
			$block .= '<v class="name" x-column="'.$localdict["attrs"]["name"].'">'.$row["FILE_NAME"].'</v>';
			$block .= '<v class="dime" x-column="'.$localdict["attrs"]["dime"].'">'.$row["WIDTH"].'x'.$row["HEIGHT"].'</v>';
			$block .= '<v class="size" x-column="'.$localdict["attrs"]["size"].'">'.ScalarFormat::fmtSizeUnit($row["FILE_SIZE"]).'</v>';
			$block .= '<v class="time" x-column="'.$localdict["attrs"]["time"].'">'.$row["KEY_MTIME"].'</v></block>';
			$this->data[] = $block;
		}
	}
	

	private function writeMediaAndTexts($localdict, $row, $type, $readonly = ''){
		$extend = SRC::getSource($row['SRC_ID'], $type);
		if($extend){
			$row = array_merge($row, $extend->toArray());
			$block = '<block name="'.$row["FILE_NAME"].'" title="'.$row["FILE_NAME"].'" x-type="'.$type.'" menu="ctx-'.$type.'" class="item '.$type.'" x-id="'.$row["ID"].'" x-suffix="'.$row["SUFFIX"].'" '.$readonly.'>';
			$block .= '<v class="sele"></v>';
			$block .= '<v class="rplc"></v>';
			$block .= '<v class="icon"></v>';
			$block .= '<v class="name" x-column="'.$localdict["attrs"]["name"].'">'.$row["FILE_NAME"].'</v>';
			if(isset($row["DURATION"])){
				$block .= '<v class="dura" x-column="'.$localdict["attrs"]["dura"].'">'.ScalarFormat::fmtTimeDuration($row["DURATION"]).'</v>';
			}else{
				$block .= '<v class="none" x-column="-">-</v>';
			}
			$block .= '<v class="size" x-column="'.$localdict["attrs"]["size"].'">'.ScalarFormat::fmtSizeUnit($row["FILE_SIZE"]).'</v>';
			$block .= '<v class="time" x-column="'.$localdict["attrs"]["time"].'">'.$row["KEY_MTIME"].'</v></block>';
			$this->data[] = $block;
		}
	}

	private function writeDocuments($localdict, $row, $readonly = ''){
		$block = '<block name="'.$row["FILE_NAME"].'" x-type="doc" menu="ctx-doc" class="item '.$row["SUFFIX"].'" x-id="'.$row["ID"].'" x-suffix="'.$row["SUFFIX"].'" '.$readonly.'>';
		$block .= '<v class="sele"></v>';
		$block .= '<v class="rplc"></v>';
		$block .= '<v class="icon"></v>';
		$block .= '<v class="name" x-column="'.$localdict["attrs"]["name"].'">'.$row["FILE_NAME"].'</v>';
		$block .= '<v class="none" x-column="-">-</v>';
		$block .= '<v class="size" x-column="'.$localdict["attrs"]["size"].'">'.ScalarFormat::fmtSizeUnit($row["FILE_SIZE"]).'</v>';
		$block .= '<v class="time" x-column="'.$localdict["attrs"]["time"].'">'.$row["KEY_MTIME"].'</v></block>';
		$this->data[] = $block;
	}
}
