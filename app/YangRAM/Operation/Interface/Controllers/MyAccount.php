<?php
namespace UOI\Controllers;

use AF\Util\OIML;
use AF\Models\Localize\SystemDict;
use Library\formattings\Timer;

class MyAccount extends \OIF\controllers\OICtrller_BC {
	public function info(){
		$visa = new VISA($this->app, $this->request, $this->passport);
		$status = (string) $visa->myStatus();
		$lang = $GLOBALS['NEWIDEA']->LANGUAGE;
		switch($status){
			case 'Runholder':
			$timer = new Timer($lang);
			$oiml = new OIML;
			$dict = SystemDict::instance()->toArray();
			$oiml->assign('username', $visa->OPERATORNAME);
			$oiml->assign('avatar', 	__DIR.$visa->AVATAR);
			$oiml->assign('welcome', 	$dict[$timer->when()]);
			$oiml->display('account');

			break;
			case 'Member':
			case 'Familiar':
			case 'Acquaintance':
			new Status(700.5, 'Need Check Visa', true);
			break;
			case 'Guest':
			new Status(700.7, 'Need Check Possport', true);
			break;
		}
	}

	public function events(){
		$visa = new VISA($this->app, $this->request, $this->passport);
		$status = (string) $visa->myStatus();
		switch($status){
			case 'Runholder':
			

			break;
			case 'Member':
			case 'Familiar':
			case 'Acquaintance':
			new Status(700.5, 'Need Check Visa', true);
			break;
			case 'Guest':
			new Status(700.7, 'Need Check Possport', true);
			break;
		}

	}
}
