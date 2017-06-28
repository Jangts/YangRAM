<?php
namespace TC\Controllers\OIViewController;

use AF\Models\Localize\SystemDict as LocalDict;
use CMF\Models\SPC\Preset;
use AF\Util\OIML;

use TC\Models\Data\RecycleRule;
use TC\Models\MenuViews\Side;
use TC\Models\BlocksViews\TypeList;

class HomepageRenderer extends \OIC\OICtrller_BC {

	public function main(){
		$this->exec(0);
	}

	public function lib(){
		$this->exec(1);
	}

	public function spc(){
		$this->exec(2);
	}

	public function xtd(){
		$this->exec(3);
	}

	private function exec($select){
		$dict = LocalDict::instance();
		$oiml = new OIML;
		$uriarr = $this->request->URI_PATH;
		$length = $this->request->LENGTH;
		$presets = Preset::all();
        $extends = RecycleRule::all();
		$side = new Side($dict, $length, $uriarr, 'nma', $presets, $extends);
		$main = new TypeList($dict, $length, $uriarr, $presets, $extends, $select);
		$oiml->assign('LOCAL', $dict);
        $oiml->assign('PAGETITLE', $dict->appname);
		$oiml->assign('LANG', $dict->code());
		$oiml->assign('SIDE', $side->render());
		$oiml->assign('MAIN', $main->render());
		$oiml->display('default');
	}
}
