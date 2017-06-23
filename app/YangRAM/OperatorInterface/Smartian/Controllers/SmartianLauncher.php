<?php
namespace Smartian\Controllers;

use Response;
use Controller;
use AF\Models\Certificates\Passport;
use AF\Models\Localize\Common as LocalDict;
use AF\Models\Ect\GSTI;
use Library\compliers\JSMin;

class SmartianLauncher extends Controller {
    public function init($lang){
        $response = Response::instance('200');
        $this->setHeaders($response);
        $lang = LocalDict::getLang($lang);
        if($filename = $this->checkFileModification(AP_CURR.'Locales/'.$lang.'.js')){
            $content = file_get_contents($filename);
            if(_USE_DEBUG_MODE_){
                $response->send($content);
            }else{
                $response->send(JSMin::minify($content));
            }
        }else{
            $response->STATUS = '304';
            $response->send();
        }
    }
    
    private function setHeaders($response) {
        $response->MIME = 'application/javascript';
        $response->setResourceCache();
    }
    
    public function welcome($lang){
		$passport = Passport::instance();
        $you = $passport->nickname;

        //本月访问
        $month = GSTI::statistics(GSTI::MONTHLY, GSTI::ALL, GSTI::PV);
        $mb = GSTI::statistics(GSTI::MONTHLY, GSTI::IS_MOBILE, GSTI::PV);

        //本月新增
        $monthnewguests = GSTI::statistics(GSTI::MONTHLY, GSTI::IS_NEWER, GSTI::PV);
        $monthmbnewguests = GSTI::statistics(GSTI::MONTHLY, GSTI::IS_NEW_MOBILE, GSTI::PV);

        //本日PV
		$pv = GSTI::statistics(GSTI::DAILY, GSTI::ALL, GSTI::PV);

         //本日IP
        $ip = GSTI::statistics(GSTI::DAILY, GSTI::ALL, GSTI::IP);

        //本日UV
        $uv = GSTI::statistics(GSTI::DAILY, GSTI::ALL, GSTI::UV);

        //本日新增
        $newguests = GSTI::statistics(GSTI::DAILY, GSTI::IS_NEWER, GSTI::PV);
        
		include AP_CURR.'Views/welcome.php';
    }
}