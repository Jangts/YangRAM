<?php
namespace UOI\Controllers;

use AF\ViewRenderers\OIML;
use AF\Models\Localize\Common as LocalDict;
use Library\formattings\Timer;

class MyAccount extends \OIC\BaseOICtrller {
	public function info(){
		$visa = new VISA($this->app, $this->request, $this->passport);
		$status = (string) $visa->myStatus();
		$lang = $GLOBALS['RUNTIME']->LANGUAGE;
		switch($status){
			case 'Runholder':
			$timer = new Timer($lang);
			$oiml = new OIML;
			$localdict = LocalDict::instance()->toArray();
			$oiml->assign('username', $visa->OPERATORNAME);
			$oiml->assign('avatar', 	__DIR.$visa->AVATAR);
			$oiml->assign('welcome', 	$localdict[$timer->when()]);
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
