<?php
namespace Pages\Controllers\OI;

use AF\Util\OIML;
use Pages\Models\Data\LocalDict;

class DefaultPage extends \OIF\controllers\OICtrller_BC {

	public function main(){
		$localdict = LocalDict::instance();
		$oiml = new OIML;
		$oiml->assign('LOCAL', $localdict);
        $oiml->assign('PAGETITLE', $localdict->appname);
		$oiml->assign('LANG', $localdict->code());
		$oiml->display('default');
	}
}
