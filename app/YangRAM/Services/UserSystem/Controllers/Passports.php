<?php
namespace CMS\Controllers;

use Status;
use Response;
use System\NIDO\DataObject;
use Model;
use Controller;
use AF\Models\App as NAM;
use AF\Models\Certificates\Passport;
/**
 *
 */
class Passports extends Controller {
	private static $sorts = [

	];

	public function get_user_avatar($uid = null){
        if($uid===null){
            $passport = Passport::instance();
            //$uid = $this->passport->uid;
            //echo($passport->avatar);
            
            Response::moveto($passport->avatar);
        }else{

        }
    }
}
