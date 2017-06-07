<?php
namespace Explorer\Models\ViewModels\traits;

use CM\SRC;
use Library\formattings\ScalarFormat;

trait src_writer {
    private function writeFolders($localdict, $row, $href, $readonly = ''){
		$block = '<block name="'.$row["name"].'" title="'.$row["name"].'" x-type="folder" menu="ctx-fld" class="item folder" x-href="'.$href.'" x-id="'.$row["id"].'" '.$readonly.'>';
	    $block .= '<vision class="sele"></vision>';
		$block .= '<vision class="rplc"></vision>';
		if($row["id"]<=6){
			$block .= '<vision class="icon"></vision><vision class="name"  x-column="'.$localdict["attrs"]["name"].'">'.$localdict["folders"][$row["id"]].'</vision>';
		}else{
			$block .= '<vision class="icon"></vision><vision class="name" x-column="'.$localdict["attrs"]["name"].'">'.$row["name"].'</vision>';
		}
		$block .= '<vision class="none" x-column="-">-</vision>';
	    $block .= '<vision class="size" x-column="'.$localdict["attrs"]["size"].'">-</vision>';
	    $block .= '<vision class="time" x-column="'.$localdict["attrs"]["time"].'">'.$row["KEY_MTIME"].'</vision></block>';
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
			$block .= '<vision class="sele"></vision>';
			$block .= '<vision class="rplc"></vision>';
			$block .= '<vision class="icon" style="background-image: url('.$src.');"></vision>';
			$block .= '<vision class="name" x-column="'.$localdict["attrs"]["name"].'">'.$row["FILE_NAME"].'</vision>';
			$block .= '<vision class="dime" x-column="'.$localdict["attrs"]["dime"].'">'.$row["WIDTH"].'x'.$row["HEIGHT"].'</vision>';
			$block .= '<vision class="size" x-column="'.$localdict["attrs"]["size"].'">'.ScalarFormat::fmtSizeUnit($row["FILE_SIZE"]).'</vision>';
			$block .= '<vision class="time" x-column="'.$localdict["attrs"]["time"].'">'.$row["KEY_MTIME"].'</vision></block>';
			$this->data[] = $block;
		}
	}
	

	private function writeMediaAndTexts($localdict, $row, $type, $readonly = ''){
		$extend = SRC::getSource($row['SRC_ID'], $type);
		if($extend){
			$row = array_merge($row, $extend->toArray());
			$block = '<block name="'.$row["FILE_NAME"].'" title="'.$row["FILE_NAME"].'" x-type="'.$type.'" menu="ctx-'.$type.'" class="item '.$type.'" x-id="'.$row["ID"].'" x-suffix="'.$row["SUFFIX"].'" '.$readonly.'>';
			$block .= '<vision class="sele"></vision>';
			$block .= '<vision class="rplc"></vision>';
			$block .= '<vision class="icon"></vision>';
			$block .= '<vision class="name" x-column="'.$localdict["attrs"]["name"].'">'.$row["FILE_NAME"].'</vision>';
			if(isset($row["DURATION"])){
				$block .= '<vision class="dura" x-column="'.$localdict["attrs"]["dura"].'">'.ScalarFormat::fmtTimeDuration($row["DURATION"]).'</vision>';
			}else{
				$block .= '<vision class="none" x-column="-">-</vision>';
			}
			$block .= '<vision class="size" x-column="'.$localdict["attrs"]["size"].'">'.ScalarFormat::fmtSizeUnit($row["FILE_SIZE"]).'</vision>';
			$block .= '<vision class="time" x-column="'.$localdict["attrs"]["time"].'">'.$row["KEY_MTIME"].'</vision></block>';
			$this->data[] = $block;
		}
	}

	private function writeDocuments($localdict, $row, $readonly = ''){
		$block = '<block name="'.$row["FILE_NAME"].'" x-type="doc" menu="ctx-doc" class="item '.$row["SUFFIX"].'" x-id="'.$row["ID"].'" x-suffix="'.$row["SUFFIX"].'" '.$readonly.'>';
		$block .= '<vision class="sele"></vision>';
		$block .= '<vision class="rplc"></vision>';
		$block .= '<vision class="icon"></vision>';
		$block .= '<vision class="name" x-column="'.$localdict["attrs"]["name"].'">'.$row["FILE_NAME"].'</vision>';
		$block .= '<vision class="none" x-column="-">-</vision>';
		$block .= '<vision class="size" x-column="'.$localdict["attrs"]["size"].'">'.ScalarFormat::fmtSizeUnit($row["FILE_SIZE"]).'</vision>';
		$block .= '<vision class="time" x-column="'.$localdict["attrs"]["time"].'">'.$row["KEY_MTIME"].'</vision></block>';
		$this->data[] = $block;
	}
}
