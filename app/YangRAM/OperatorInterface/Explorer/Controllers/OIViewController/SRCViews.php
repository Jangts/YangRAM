<?php
namespace Explorer\Controllers\OIViewController;

use AF\Models\Localize\SystemDict;
use Explorer\Models\ViewModels\Side;
use Explorer\Models\ViewModels\Header;
use Explorer\Models\ViewModels\Homepage;
use Explorer\Models\ViewModels\SRC;

class SRCViews extends \OIC\OICtrller_BC {
	use traits;

	public function main(){
        $dict = SystemDict::instance();
        $uriarr = $this->request->URI_PATH;
        $length = $this->request->LENGTH;
        $menus = self::getMeunCurr('src', null);
        $sidebar = new Side($dict, $menus, 'src', null);
        $topvision = new Header($dict, $uriarr, $length, 'src', null, null);
        $content = new Homepage($dict, 'src');;
        $this->show('default', $dict, 'src', $sidebar->toArray(), $topvision->render(), $content->render(), $uriarr, $length);
    }
    
    public function all($folder = null, $filetype = 'all'){
        $dict = SystemDict::instance();
        $uriarr = $this->request->URI_PATH;
        $length = $this->request->LENGTH;
        $menus = self::getMeunCurr('src', $filetype);
        $sidebar = new Side($dict, $menus, 'src', $filetype, $folder);
        $topvision = new Header($dict, $uriarr, $length, 'src', $filetype, $folder);
        $content = new SRC($dict, $filetype, $folder);
        $this->show('default', $dict, 'src', $sidebar->toArray(), $topvision->render(), $content->render($dict), $uriarr, $length);
    }

	public function img($folder = null){
		$this->all($folder, 'img');
	}

	public function txt($folder = null){
		$this->all($folder, 'txt');
	}

	public function doc($folder = null){
		$this->all($folder, 'doc');
	}

	public function wav($folder = null){
		$this->all($folder, 'wav');
	}

	public function vod($folder = null){
		$this->all($folder, 'vod');
	}

	public function zip($folder = null){
		$this->all($folder, 'zip');
	}

	public function ect($folder = null){
		$this->all($folder, 'ect');
	}
}
