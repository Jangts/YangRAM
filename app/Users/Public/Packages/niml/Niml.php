<?php
namespace Packages\niml;

use Storage;

use CMF\Models\EMC;
use CMF\Models\GEC;
use CMF\Models\SPCLite;
use CMF\Models\SPC\Category;
use CMF\Models\SPC\Defaults;
use CMF\Models\SPC\Preset;
use CMF\Models\SPC\Tag;
use CMF\Models\SPC;

include_once('lib/NIML.php');

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

	protected function getWords(){
		$lang = $GLOBALS['NEWIDEA']->LANGUAGE;
		$file = PATH_VIEW.$this->theme."/locales/".$lang.".json";
		if(is_file($file)){
			$json = file_get_contents($file);
			$dict = json_decode($json, true);
			if($dict){
				return $dict;
			}
		}
		return [];
	}

	public function getFilenames($template, $is_include = false){
		if($is_include==false){
			$this->assign("__DOMAIN", '//'.HOST);
			$this->assign("__SRCDIR", VIEW_PID.$this->theme."/");
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