<?php
namespace OIC;

use Status;
use Request;
use AF\Models\Certificates\Passport;
use Application;
use Controller;

abstract class BaseOISubmitter extends \AF\Controllers\BaseSubmitter {
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
}
