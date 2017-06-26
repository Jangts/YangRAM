<?php
namespace CMS\Controllers;

use Status;
use Response;
use Tangram\NIDO\DataObject;
use Model;
use Controller;
use AF\Models\App as NAM;
use AF\Models\Certificates\StdPassport;
/**
 *
 */
class Passports extends Controller {
	private static $sorts = [

	];

	public function get_user_avatar($uid = null){
        if($uid===null){
            $passport = StdPassport::instance();
            //$uid = $this->passport->uid;
            //echo($passport->avatar);
            
            Response::moveto($passport->avatar);
        }else{

        }
    }
}
