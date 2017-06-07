<?php
namespace Explorer\Controllers\OIViewController;

use AF\Models\Localize\Common as LocalDict;
use Explorer\Models\ViewModels\Side;
use Explorer\Models\ViewModels\Header;
use Explorer\Models\ViewModels\Homepage;
use Explorer\Models\ViewModels\SPC;

class SPCViews extends \OIC\BaseOICtrller {
    use traits;

	public function main(){
        $localdict = LocalDict::instance();
        $uriarr = $this->request->URI_PATH;
        $length = $this->request->LENGTH;
        $menus = self::getMeunCurr('spc', null);
        $sidebar = new Side($localdict, $menus, 'spc', null);
        $topvision = new Header($localdict, $uriarr, $length, 'spc', null, null);
        $content = new Homepage($localdict, 'spc');
        $this->show('default', $localdict, 'spc', $sidebar->toArray(), $topvision->render(), $content->render(), $uriarr, $length);
    }
    
    public function preset($presetid = null, $month = null){
        $localdict = LocalDict::instance();
        $uriarr = $this->request->URI_PATH;
        $length = $this->request->LENGTH;
        $menus = self::getMeunCurr('spc', $presetid);
        $sidebar = new Side($localdict, $menus, 'spc', $presetid);
        $topvision = new Header($localdict, $uriarr, $length, 'spc', $presetid, $month);
        $content = new SPC($localdict, $presetid, $month);
        $this->show('default', $localdict, 'spc', $sidebar->toArray(), $topvision->render(), $content->render($localdict), $uriarr, $length);
    }
}