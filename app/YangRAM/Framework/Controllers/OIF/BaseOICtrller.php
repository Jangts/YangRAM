<?php
namespace OIC;

use Status;
use Request;
use AF\Models\Certificates\Passport;
use Application;
use Controller;

abstract class BaseOICtrller extends \AF\Controllers\BaseCtrller {
    protected
    $request = NULL,
    $app = NULL,
    $passport = NULL;

    public function __construct(Application $app, Request $request){
		$visa = new OperatorVISACtrller($app, $request);
		$status = (string) $visa->myStatus();
		if($status==='Runholder'){
			$this->request = $request;
        	$this->app = $app;
        	$this->passport = Passport::instance();
		}else{
            new Status(700.5, '', 'Current Status [ '.$status.' ]',true);
        }
	}

    protected function modified($filename) {
		$lastModified = filemtime($filename);
		if (isset($_SERVER["HTTP_IF_MODIFIED_SINCE"])){
			if (strtotime($_SERVER["HTTP_IF_MODIFIED_SINCE"]) < $lastModified) {
				return true;
			}
			return false;
		}
		if (isset($_SERVER['HTTP_IF_UNMODIFIED_SINCE'])){
			if (strtotime($_SERVER['HTTP_IF_UNMODIFIED_SINCE']) > $lastModified) {
				return true;
			}
			return false;
		}
		return true;
	}
}
