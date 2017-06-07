<?php
namespace Explorer\Controllers\OIViewController;

use AF\Models\Localize\Common as LocalDict;
use Explorer\Models\ViewModels\Side;
use Explorer\Models\ViewModels\Header;
use Explorer\Models\ViewModels\Homepage;
use Explorer\Models\ViewModels\SearchResult;

class CommonViews extends \OIC\BaseOICtrller {
	use traits;

	public function main(){
		$localdict = LocalDict::instance();
		$menus = self::getMeunCurr('default', null);
		$sidebar = new Side($localdict, $menus, 'default', null);
		$uriarr = $this->request->URI_PATH;
		$length = $this->request->LENGTH;
		$topvision = new Header($localdict, $uriarr, $length, 'default', null, null);
		$content = new Homepage($localdict);
		$this->show('default', $localdict, 'default', $sidebar->toArray(), $topvision->render(), $content->render(), $uriarr, $length);
	}

	public function search() {
		if(isset($this->request->PARAMS->kw)&&$this->request->PARAMS->kw!=''){
			$kw = $this->request->PARAMS->kw;
		}else{
			$kw = '';
		}
		$html = '';
        $localdict = LocalDict::instance();
        $uriarr = $this->request->URI_PATH;
        $length = $this->request->LENGTH;
		$menus = self::getMeunCurr('sch', null);
		$sidebar = new Side($localdict, $menus, 'sch', null);
		$topvision = new Header($localdict, $uriarr, $length, 'sch', null, null);
		$content = new SearchResult($localdict, $kw);
        $this->show('default', $localdict, 'sch', $sidebar->toArray(), $topvision->render(), $content->render($localdict), $uriarr, $length, $kw);
	}
}
