<?php
namespace Explorer\Models\ViewModels\traits;

use CMF\Models\SRC;
use Library\formattings\ScalarFormat;

trait spc_writer {
    private function writeMonths($localdict, $row, $href){
		$block = '<block title="'.$row["month"].'" class="item folder" x-type="folder" x-href="'.$href.'" readonly>';
		$block .= '<v class="sele"></v>';
		$block .= '<v class="rplc"></v>';
		$block .= '<v class="icon"></v>';
		$block .= '<v class="name" x-column="'.$localdict["attrs"]["month"].'">'.$row["month"].'</v>';
		$block .= '<v class="none" x-column="-">-</v>';
		$block .= '<v class="size" x-column="'.$localdict["attrs"]["size"].'">-</v>';
		$block .= '<v class="time" x-column="'.$localdict["attrs"]["time"].'">-</v></block>';
		$this->data[] = $block;
	}

	private function writeContents($localdict, $preset, $presetinfo, $row){
		$block = '<block title="'.$row["TITLE"].'" x-type="spc" menu="ctx-set" x-preset="'.$preset.'" x-base="'.$presetinfo->basic_type.'" x-name="'.$presetinfo->name.'" class="item set" x-id="'.$row["ID"].'">';
		$block .= '<v class="sele"></v>';
		$block .= '<v class="rplc"></v>';
		$block .= '<v class="icon"></v>';
		$block .= '<v class="name" x-column="'.$localdict["attrs"]["sname"].'">'.$row["TITLE"].'</v>';
		$block .= '<v class="none" x-column="-">-</v>';
		$block .= '<v class="size" x-column="'.$localdict["attrs"]["size"].'">-</v>';
		$block .= '<v class="time" x-column="'.$localdict["attrs"]["time"].'">'.$row["KEY_MTIME"].'</v></block>';
		$this->data[] = $block;
	}
}
