<?php
namespace UOI\Controllers;

use Response;

class System extends \Controller {
	public function loadInterface($passport){
		$__Style = $this->checkStyle();
		$__Lang =  $this->checkLanguage();
		$this->VISA = new VISA($this->app, $this->request, $this->passport);
		$avatar = $this->VISA->AVATAR;
		$username = $passport->username;
		$response = Response::instance('200');
		$response->sendHeaders();
		include PATH_UOI.'Views/Evn.php';
	}

	public function loadLoginPanel(){
		$__Style = $this->checkStyle();
		$__Lang =  $this->checkLanguage();
		if(isset($_COOKIE['operator'])){
			$username = $_COOKIE['operator'];
			$avatar = $_COOKIE['opavatar'];
		}else{
			$username = '';
			$avatar = UOI_PID.'Sources/styles/icon.svg';
		}
		$response = Response::instance('200');
		$response->sendHeaders();
		include PATH_UOI.'Views/Log.php';
	}

	private function checkStyle(){
		return 'default';
	}

	private function checkLanguage(){
		$lang_check_result = $GLOBALS['NEWIDEA']->check_lang(PATH_LANGS.'{{lang}}', true);
		if($lang_check_result){
			return $lang_check_result[0];
		}
		return _LANG_;
	}
}
