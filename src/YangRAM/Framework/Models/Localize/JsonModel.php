<?php
namespace AF\Models\Localize;
use Status;
use System\NIDO\DataObject;
use Application;

class BaseModel extends BaseModel {
	public function __construct(){
		$sysla = $GLOBALS['RUNTIME']->LANGUAGE;
		$filename = $this->checklang(AP_CURR, $sysla);
		$this->data = json_decode(file_get_contents($filename));
	}
	
	protected function checklang($path, $lang){
		if(!empty($this->langs[0])&&is_string($this->langs[0])){
			$GLOBALS['RUNTIME']->LANGUAGE = $this->langs[0];
		}
		$lang_check_result = $GLOBALS['RUNTIME']->check_lang($path.$this->dir.'/{{lang}}.json', false, $lang);
		if($lang_check_result){
			$this->code = $lang_check_result[0];
			return $lang_check_result[1];
		}
		new Status(708, 'Language file of ['.$lang.'] not found', true);
	}
}
