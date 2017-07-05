<?php
namespace Fourm\Controllers;

use Status;
use Response;
use Tangram\NIDO\Guest;


use Packages\niml\Niml;
use AF\Models\Util\SYSI;

use GPS\Models\Data\UserInfo;
use GPS\Models\Data\Page;

use GPS\Models\ViewModels\FE\SearchPage;

class Pages extends \Controller {
	private
	$pages = NULL,
	$niml = NULL;

	private function assign($renderer){
		$sys = new SYSI;
		$sys = $sys->toArray();
		foreach($sys as $key => $val){
			$renderer->assign('__SYS_'.$key, $val);
		}
		$usr = new UserInfo;
		$usr = $usr->toArray();
		foreach($usr as $key => $val){
			$renderer->assign('__User_'.$key, $val);
		}
		$column = $this->request->COLUMN;
		$renderer->assign('__Column', $column->__COL_ALIAS);
		$renderer->assign('__Columns', $column->__COL_TREE);
		$gst = new Guest;
		$gst->record($column->id());
	}

	public function main(){
		header("Content-Type: text/html; charset=UTF-8");
		echo '<title>页面</title>';
		echo '<h2 style="font-size:48px;">这是一个论坛程序！</h2>';
	}

}
