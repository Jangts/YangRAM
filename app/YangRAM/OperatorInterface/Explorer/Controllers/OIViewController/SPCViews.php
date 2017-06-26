<?php
namespace Explorer\Controllers\OIViewController;

use AF\Models\Localize\SystemDict;
use Explorer\Models\ViewModels\Side;
use Explorer\Models\ViewModels\Header;
use Explorer\Models\ViewModels\Homepage;
use Explorer\Models\ViewModels\SPC;

class SPCViews extends \OIC\OICtrller_BC {
    use traits;

	public function main(){
        $dict = SystemDict::instance();
        $uriarr = $this->request->URI_PATH;
        $length = $this->request->LENGTH;
        $menus = self::getMeunCurr('spc', null);
        $sidebar = new Side($dict, $menus, 'spc', null);
        $topvision = new Header($dict, $uriarr, $length, 'spc', null, null);
        $content = new Homepage($dict, 'spc');
        $this->show('default', $dict, 'spc', $sidebar->toArray(), $topvision->render(), $content->render(), $uriarr, $length);
    }
    
    public function preset($presetid = null, $month = null){
        $dict = SystemDict::instance();
        $uriarr = $this->request->URI_PATH;
        $length = $this->request->LENGTH;
        $menus = self::getMeunCurr('spc', $presetid);
        $sidebar = new Side($dict, $menus, 'spc', $presetid);
        $topvision = new Header($dict, $uriarr, $length, 'spc', $presetid, $month);
        $content = new SPC($dict, $presetid, $month);
        $this->show('default', $dict, 'spc', $sidebar->toArray(), $topvision->render(), $content->render($dict), $uriarr, $length);
    }
}