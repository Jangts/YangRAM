<?php
namespace TC\Controllers\OIViewController;

use AF\Models\Localize\Common as LocalDict;
use CM\SPC\Preset;
use AF\ViewRenderers\OIML;

use TC\Models\Data\RecycleRule;
use TC\Models\MenuViews\Side;
use TC\Models\BlocksViews\TypeList;

class HomepageRenderer extends \OIC\BaseOICtrller {

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
		$localdict = LocalDict::instance();
		$oiml = new OIML;
		$uriarr = $this->request->URI_PATH;
		$length = $this->request->LENGTH;
		$presets = Preset::all();
        $extends = RecycleRule::all();
		$side = new Side($localdict, $length, $uriarr, 'nma', $presets, $extends);
		$main = new TypeList($localdict, $length, $uriarr, $presets, $extends, $select);
		$oiml->assign('LOCAL', $localdict);
        $oiml->assign('PAGETITLE', $localdict->appname);
		$oiml->assign('LANG', $localdict->code());
		$oiml->assign('SIDE', $side->render());
		$oiml->assign('MAIN', $main->render());
		$oiml->display('default');
	}
}
