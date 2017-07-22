<?php
namespace Explorer\Controllers\OIViewController;

use AF\Models\Localize\SystemDict;
use Explorer\Models\ViewModels\Side;
use Explorer\Models\ViewModels\Header;
use Explorer\Models\ViewModels\Homepage;
use Explorer\Models\ViewModels\SearchResult;

class CommonViews extends \OIF\controllers\OICtrller_BC {
	use traits;

	public function main(){
		$dict = SystemDict::instance();
		$menus = self::getMeunCurr('default', null);
		$sidebar = new Side($dict, $menus, 'default', null);
		$uriarr = $this->request->URI_PATH;
		$length = $this->request->LENGTH;
		$topvision = new Header($dict, $uriarr, $length, 'default', null, null);
		$content = new Homepage($dict);
		$this->show('default', $dict, 'default', $sidebar->toArray(), $topvision->render(), $content->render(), $uriarr, $length);
	}

	public function search() {
		if(isset($this->request->PARAMS->kw)&&$this->request->PARAMS->kw!=''){
			$kw = $this->request->PARAMS->kw;
		}else{
			$kw = '';
		}
		$html = '';
        $dict = SystemDict::instance();
        $uriarr = $this->request->URI_PATH;
        $length = $this->request->LENGTH;
		$menus = self::getMeunCurr('sch', null);
		$sidebar = new Side($dict, $menus, 'sch', null);
		$topvision = new Header($dict, $uriarr, $length, 'sch', null, null);
		$content = new SearchResult($dict, $kw);
        $this->show('default', $dict, 'sch', $sidebar->toArray(), $topvision->render(), $content->render($dict), $uriarr, $length, $kw);
	}
}
