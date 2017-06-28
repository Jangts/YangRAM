<?php
namespace AF\Models\Localize;

use Status;

final class SystemDict extends Dictionary {
	public static function getLang($lang){
		$lang_check_result = $GLOBALS['RUNTIME']->check_lang(PATH_LANGS.'{{lang}}', true, $lang);
		if($lang_check_result){
			return $lang_check_result[0];
		}
		return 'en-us';
	}

	public function __construct($appid = AI_CURR){
		global $RUNTIME;
		$appid = strtolower($appid);
		$sysla = $RUNTIME->LANGUAGE;
		$appla = $this->checklang($sysla);
		if(is_file(PATH_LANGS.$appla.'/'.$appid.'.php')){
			$this->data = include PATH_LANGS.$appla.'/'.$appid.'.php';
		}else{
			$lang_check_result = $GLOBALS['RUNTIME']->check_lang(PATH_LANGS.'{{lang}}', true, $sysla);
			new Status(702, 'Language file not found', 'Language file ['.PATH_LANGS.$appla.'/'.$appid.'.php'.', '.$lang_check_result[1].'] not found.', true);
		}
	}
	
	protected function checklang($lang, $foo='bar'){
		$lang = self::getLang($lang);
		$this->code = $lang;
		return $lang;
	}
}