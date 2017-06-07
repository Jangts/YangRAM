<?php
namespace Packages\tplengines;
use Storage;

use CM\EMC;
use CM\GEC;
use CM\SPCLite;
use CM\SPC\Category;
use CM\SPC\Defaults;
use CM\SPC\Preset;
use CM\SPC\Tag;
use CM\SPC;

include_once('niml/NIML.php');

class Niml extends \NIML {

	public static function updatetemplateCache($templates=NULL){
		if(is_array($templates)){
			foreach($templates as $template){
				\unlink(PATH_DAT_TPL.hash('md4', $template[0].($template[1] ? $template[1] : '')).".php");
			}
		}else{
			Storage::clearPath(PATH_DAT_TPL);
		}
	}

	public
	$leftTAG = '{{',
	$rightTAG = '}}';

	public function getFilenames($template, $is_include = false){
		if($is_include==false){
			$this->assign("__DOMAIN", '//'.HOST);
			$this->assign("__SRCDIR", VIEW_PID.$this->theme."/");
			$this->assign("__AF_SRCDIR", NIAF_PID."Sources/");
		}
		return [PATH_VIEW.$this->theme."/nimls/".$template, PATH_DAT_TPL.'niml/'.hash('md4', $this->theme.$template).".php"];
	}

	public function label($label){
		if(is_numeric($label)){
			$content = EMC::byId($label);
		}else{
			$content = EMC::byLabel($label);
		}
		if($content){
			echo $content->code;
		}
	}
}