<?php
namespace OIF\controllers;

use Status;
use Request;
use AF\Models\Certificates\StdPassport;
use Application;
use Controller;

abstract class OICtrller_BC extends \AF\Controllers\Controller_BC {
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
        	$this->passport = StdPassport::instance();
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
