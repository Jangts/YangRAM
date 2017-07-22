<?php
namespace Explorer\Controllers\OIViewController;

use AF\Util\OIML;

trait traits {
	private static function getMeunCurr($datatype, $itemtype){
		$menus = array(
			'srcall'	=>	'',
			'srcimg'	=>	'',
			'srctxt'	=>	'',
			'srcdoc'	=>	'',
			'srcwav'	=>	'',
			'srcvod'	=>	'',
			'srczip'	=>	'',
			'srcect'	=>	''	
		);
		if(isset($itemtype)){
			$menus[$datatype.$itemtype] = 'curr';
		}
		return $menus;
	}

	private function show($template, $localdict, $datatype, $sidebar, $topvision, $content, $uriarr, $length, $placeholder = 'Search YangRAM Explorer'){
		$oiml = new OIML;
		$oiml->assign('LOCAL', $localdict);
		$oiml->assign('SIDEBAR', $sidebar);
		$oiml->assign('TOPVISION', $topvision);
		$oiml->assign('PLACEHOLDER', $placeholder);
		$path = '';
		for($i = 3; $i < $length; $i++){
			$path .= $uriarr[$i].'/';
		}
		$oiml->assign('CONTENTSRC', $path.'?local');
		$oiml->assign('CONTENT', $content);
        $oiml->assign('LANGUAGE', $localdict->code);
        $oiml->assign('PAGETITLE', $localdict->$datatype.' - Content Explorer');
		if($length>3){
			if(isset($_GET["lt"])){
				$oiml->assign('APP_MCTYPE', $_GET["lt"]);
			}else{
				$oiml->assign('APP_MCTYPE', 'tile');
			}
			$oiml->assign('PAGETITLE', $localdict->$datatype.' - Content Explorer');
		}
		$oiml->display($template);
	}
}
