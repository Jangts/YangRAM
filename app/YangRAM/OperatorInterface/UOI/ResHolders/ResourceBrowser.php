<?php
namespace UOI\ResHolders;

use Status;
use AF\Models\Certificates\StdPassport;
use UOI\Controllers\System;
use UOI\Controllers\VISA;

class ResourceBrowser extends \AF\ResourceHolders\ResourceBrowser_BC {
	public function __construct($app, $request){
		$passport = StdPassport::instance();
		$system = new System($app, $request);
		$visa = new VISA($app, $request, $passport);
		$status = (string) $visa->myStatus();
		//var_dump($status);
		//die;
		switch ($status) {
			case 'Runholder':
			$system->loadInterface($passport);
			break;
			case 'Member':
			case 'Familiar':
			case 'Acquaintance':
			case 'Guest':
			$system->loadLoginPanel();
			break;
			default:
			new Status(700.6, '', 'Unknow Visa Status!' . $status, true);
		}
	}
}
