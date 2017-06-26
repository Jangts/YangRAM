<?php
namespace AF\Models\Localize;
use Status;
use Tangram\NIDO\DataObject;
use Application;

class Dictionary extends DataObject {
	protected static $instance = NULL;

	public static function instance(){
		if(self::$instance === NULL){
			self::$instance = new static;
		}
		return self::$instance;
	}

	public static function arr(){
		if(self::$instance === NULL){
			self::$instance = new static;
		}
		return self::$instance->toArray();
	}

	public static function json(){
		if(self::$instance === NULL){
			self::$instance = new static();
		}
		return self::$instance->toJson();
	}

	public static function replaceroot($str){
		return str_replace(strtolower(__DIR), '{{@root_url}}', $str);
	}

	public static function restoreroot($str){
		return str_replace('{{@root_url}}', strtolower(__DIR), $str);
	}

	protected
	$code,
	$langs = ['en-us','zh-cn'],
	$dir = 'Locales';

	public function __construct(){
		$sysla = $GLOBALS['RUNTIME']->LANGUAGE;
		$filename = $this->checklang(AP_CURR, $sysla);
		$this->data = include $filename;
	}
	
	protected function checklang($path, $lang){
		if(!empty($this->langs[0])&&is_string($this->langs[0])){
			$GLOBALS['RUNTIME']->LANGUAGE = $this->langs[0];
		}
		$lang_check_result = $GLOBALS['RUNTIME']->check_lang($path.$this->dir.'/{{lang}}.php', false, $lang);
		if($lang_check_result){
			$this->code = $lang_check_result[0];
			return $lang_check_result[1];
		}
		new Status(708, 'Language file of ['.$lang.'] not found', true);
	}

	public function code(){
        return $this->code;
	}
}
