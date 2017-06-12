<?php
namespace GPS\Controllers\OI;

use AF\ViewRenderers\OIML;
use GPS\Models\Data\LocalDict;

class DefaultPage extends \OIC\BaseOICtrller {

	public function main(){
		$localdict = LocalDict::instance();
		$oiml = new OIML;
		$oiml->assign('LOCAL', $localdict);
        $oiml->assign('PAGETITLE', $localdict->appname);
		$oiml->assign('LANG', $localdict->code());
		$oiml->display('default');
	}
}
