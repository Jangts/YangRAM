<?php
namespace Studio\Stk\Controllers\OI;

use AF\ViewRenderers\OIML;
use Studio\Stk\Models\LocalDict;
use Studio\Stk\Models\DefaultPageVM;

class DefaultPageVC extends \OIC\BaseOICtrller {

	public function main(){
		$localdict = LocalDict::instance();
		$oiml = new OIML;
		$oiml->assign('LOCAL', $localdict);
        $oiml->assign('PAGETITLE', $localdict->appname);
		$oiml->assign('LANG', $localdict->code());
		$oiml->assign('TOP', array('home_list'=>'', 'new_mod'=>'unavailable'));
		$oiml->assign('CONTENT', (new DefaultPageVM($localdict))->render());
		$oiml->display('common');
		return $this;
	}
}
