<?php
namespace Pages\Controllers\OI;

use AF\ViewRenderers\OIML;
use Pages\Models\Data\LocalDict;
use Pages\Models\ViewModels\OI\StartPage;
use Pages\Models\ViewModels\OI\PageList;
use Pages\Models\ViewModels\OI\Form;

class OpenMain extends \Controller {

	public function main(){
        $localdict = LocalDict::instance();
		$content = new StartPage($localdict);
        echo $content->render();
	}

    private function list($type){
        $oiml = new OIML;
        $localdict = LocalDict::instance();
        $uriarr = $this->request->URI_PATH;
		$length = $this->request->LENGTH;
        $params = $this->request->PARAMS;
        $data = new PageList($type, $localdict, $uriarr, $length, $params);
		$oiml->assign('LOCAL', $localdict);
        $oiml->assign('PAGE_SIDE', $data->render('side'));
        $oiml->assign('PAGE_LIST', $data->render('list'));
        $oiml->assign('PAGE_PAGE', $data->render('page'));
		$oiml->display('list');
    }

    public function singlepage(){
        $this->list(1);
    }

    public function generalpage(){
        $this->list(2);
    }

    public function listpage(){
        $this->list(3);
    }

    public function detailpage(){
        $this->list(4);
    }

    public function userpage(){
        $this->list(5);
    }

    public function searchpage(){
        $this->list(6);
    }

    public function redirectings(){
        $this->list(7);
    }

    public function form(){
        $oiml = new OIML;
        $localdict = LocalDict::instance();
        $uriarr = $this->request->URI_PATH;
		$length = $this->request->LENGTH;
        $params = $this->request->PARAMS;
        $post = $this->request->FORM;
        $pid = $post->id;
        $data = new Form($pid, $localdict, $length, $params);
        $oiml->assign('LOCAL', $localdict);
        $oiml->assign('PID', $pid);
        $oiml->assign('FORM', $data->render());
		$oiml->display('form');
    }
}
