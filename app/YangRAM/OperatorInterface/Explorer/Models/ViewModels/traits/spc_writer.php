<?php
namespace Explorer\Models\ViewModels\traits;

use CM\SRC;
use Library\formattings\ScalarFormat;

trait spc_writer {
    private function writeMonths($localdict, $row, $href){
		$block = '<block title="'.$row["month"].'" class="item folder" x-type="folder" x-href="'.$href.'" readonly>';
		$block .= '<vision class="sele"></vision>';
		$block .= '<vision class="rplc"></vision>';
		$block .= '<vision class="icon"></vision>';
		$block .= '<vision class="name" x-column="'.$localdict["attrs"]["month"].'">'.$row["month"].'</vision>';
		$block .= '<vision class="none" x-column="-">-</vision>';
		$block .= '<vision class="size" x-column="'.$localdict["attrs"]["size"].'">-</vision>';
		$block .= '<vision class="time" x-column="'.$localdict["attrs"]["time"].'">-</vision></block>';
		$this->data[] = $block;
	}

	private function writeContents($localdict, $preset, $presetinfo, $row){
		$block = '<block title="'.$row["TITLE"].'" x-type="spc" menu="ctx-set" x-preset="'.$preset.'" x-base="'.$presetinfo->basic_type.'" x-name="'.$presetinfo->name.'" class="item set" x-id="'.$row["ID"].'">';
		$block .= '<vision class="sele"></vision>';
		$block .= '<vision class="rplc"></vision>';
		$block .= '<vision class="icon"></vision>';
		$block .= '<vision class="name" x-column="'.$localdict["attrs"]["sname"].'">'.$row["TITLE"].'</vision>';
		$block .= '<vision class="none" x-column="-">-</vision>';
		$block .= '<vision class="size" x-column="'.$localdict["attrs"]["size"].'">-</vision>';
		$block .= '<vision class="time" x-column="'.$localdict["attrs"]["time"].'">'.$row["KEY_MTIME"].'</vision></block>';
		$this->data[] = $block;
	}
}
