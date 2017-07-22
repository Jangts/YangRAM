<?php
namespace TC\Controllers\OIViewController;

use AF\Models\Localize\SystemDict;
use CMF\Models\SPC\Preset;
use AF\Util\OIML;

use TC\Models\Data\RecycleRule;
use TC\Models\MenuViews\Side;
use TC\Models\SheetsViews\LIBList;
use TC\Models\SheetsViews\SPCList;
use TC\Models\SheetsViews\XTDList;

class ListPageRenderer extends \OIF\controllers\OICtrller_BC {

	public function lib($type){
		$dict = SystemDict::instance();
		$oiml = new OIML;
		$uriarr = $this->request->URI_PATH;
		$length = $this->request->LENGTH;

		$params = $this->request->PARAMS;
		$sort = empty($params->sort) ? 'nma' : $params->sort;
		$cpage = empty($params->page) ? 1 : $params->page;

        $presets = Preset::all();
        $extends = RecycleRule::all();
        $side = new Side($dict, $length, $uriarr, $sort, $presets, $extends);
        $main = new LIBList($dict, $length, $uriarr, $cpage, $sort, $type);
		$oiml->assign('LOCAL', $dict);
        $oiml->assign('PAGETITLE', $dict->appname);
		$oiml->assign('LANG', $dict->code());
		$oiml->assign('SIDE', $side->render());
		$oiml->assign('MAIN', $main->render());
		$oiml->display('default');
	}

    public function spc($preset_id){
        $dict = SystemDict::instance();
		$oiml = new OIML;
		$uriarr = $this->request->URI_PATH;
		$length = $this->request->LENGTH;

		$params = $this->request->PARAMS;
		$sort = empty($params->sort) ? 'nma' : $params->sort;
		$cpage = empty($params->page) ? 1 : $params->page;

        $presets = Preset::all();
        $extends = RecycleRule::all();
        $side = new Side($dict, $length, $uriarr, $sort, $presets, $extends);
        $main = new SPCList($dict, $length, $uriarr, $cpage, $sort, $preset_id);
		$oiml->assign('LOCAL', $dict);
        $oiml->assign('PAGETITLE', $dict->appname);
		$oiml->assign('LANG', $dict->code());
		$oiml->assign('SIDE', $side->render());
		$oiml->assign('MAIN', $main->render());
		$oiml->display('default');
    }

    public function xtd($rule_id){
		$dict = SystemDict::instance();
		$oiml = new OIML;
		$uriarr = $this->request->URI_PATH;
		$length = $this->request->LENGTH;

		$params = $this->request->PARAMS;
		$sort = empty($params->sort) ? 'nma' : $params->sort;
		$cpage = empty($params->page) ? 1 : $params->page;

        $presets = Preset::all();
        $extends = RecycleRule::all();
        $side = new Side($dict, $length, $uriarr, $sort, $presets, $extends);
        $main = new XTDList($dict, $length, $uriarr, $cpage, $sort, $rule_id);
		$oiml->assign('LOCAL', $dict);
        $oiml->assign('PAGETITLE', $dict->appname);
		$oiml->assign('LANG', $dict->code());
		$oiml->assign('SIDE', $side->render());
		$oiml->assign('MAIN', $main->render());
		$oiml->display('default');
    }
}
