<?php
namespace Studio\Stk\Controllers\OI;

use AF\ViewRenderers\OIML;
use Studio\Stk\Models\LocalDict;
use Studio\Stk\Models\ListPageModels;

class ListPageVC extends \OIC\BaseOICtrller {

	public function main(){
		$oiml = new OIML;
        $localdict = LocalDict::instance();
        $uriarr = $this->request->URI_PATH;
		$length = $this->request->LENGTH;
        $params = $this->request->PARAMS;

		$oiml->assign('LOCAL', $localdict);
        $oiml->assign('PAGETITLE', $localdict->appname);
		$oiml->assign('LANG', $localdict->code());
		$oiml->assign('TOP', array('home_list'=>'', 'new_mod'=>'unavailable'));

		$base = new ListPageModels\Params($params);
        $side = new ListPageModels\Menu($localdict, $base);
        $main = new ListPageModels\Sheet($localdict, $base);

		$oiml->assign('SIDE', $side->render('menu'));
		$oiml->assign('MAIN', $main->render('sheet'));
		$oiml->display('list');
		return $this;
	}
}