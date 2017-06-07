<?php
namespace TC\Controllers\OIViewController;

use AF\Models\Localize\Common as LocalDict;
use CM\SPC\Preset;
use AF\ViewRenderers\OIML;

use TC\Models\Data\RecycleRule;
use TC\Models\MenuViews\Side;
use TC\Models\SheetsViews\LIBList;
use TC\Models\SheetsViews\SPCList;
use TC\Models\SheetsViews\XTDList;

class ListPageRenderer extends \OIC\BaseOICtrller {

	public function lib($type){
		$localdict = LocalDict::instance();
		$oiml = new OIML;
		$uriarr = $this->request->URI_PATH;
		$length = $this->request->LENGTH;

		$params = $this->request->PARAMS;
		$sort = empty($params->sort) ? 'nma' : $params->sort;
		$cpage = empty($params->page) ? 1 : $params->page;

        $presets = Preset::all();
        $extends = RecycleRule::all();
        $side = new Side($localdict, $length, $uriarr, $sort, $presets, $extends);
        $main = new LIBList($localdict, $length, $uriarr, $cpage, $sort, $type);
		$oiml->assign('LOCAL', $localdict);
        $oiml->assign('PAGETITLE', $localdict->appname);
		$oiml->assign('LANG', $localdict->code());
		$oiml->assign('SIDE', $side->render());
		$oiml->assign('MAIN', $main->render());
		$oiml->display('default');
	}

    public function spc($preset_id){
        $localdict = LocalDict::instance();
		$oiml = new OIML;
		$uriarr = $this->request->URI_PATH;
		$length = $this->request->LENGTH;

		$params = $this->request->PARAMS;
		$sort = empty($params->sort) ? 'nma' : $params->sort;
		$cpage = empty($params->page) ? 1 : $params->page;

        $presets = Preset::all();
        $extends = RecycleRule::all();
        $side = new Side($localdict, $length, $uriarr, $sort, $presets, $extends);
        $main = new SPCList($localdict, $length, $uriarr, $cpage, $sort, $preset_id);
		$oiml->assign('LOCAL', $localdict);
        $oiml->assign('PAGETITLE', $localdict->appname);
		$oiml->assign('LANG', $localdict->code());
		$oiml->assign('SIDE', $side->render());
		$oiml->assign('MAIN', $main->render());
		$oiml->display('default');
    }

    public function xtd($rule_id){
		$localdict = LocalDict::instance();
		$oiml = new OIML;
		$uriarr = $this->request->URI_PATH;
		$length = $this->request->LENGTH;

		$params = $this->request->PARAMS;
		$sort = empty($params->sort) ? 'nma' : $params->sort;
		$cpage = empty($params->page) ? 1 : $params->page;

        $presets = Preset::all();
        $extends = RecycleRule::all();
        $side = new Side($localdict, $length, $uriarr, $sort, $presets, $extends);
        $main = new XTDList($localdict, $length, $uriarr, $cpage, $sort, $rule_id);
		$oiml->assign('LOCAL', $localdict);
        $oiml->assign('PAGETITLE', $localdict->appname);
		$oiml->assign('LANG', $localdict->code());
		$oiml->assign('SIDE', $side->render());
		$oiml->assign('MAIN', $main->render());
		$oiml->display('default');
    }
}
