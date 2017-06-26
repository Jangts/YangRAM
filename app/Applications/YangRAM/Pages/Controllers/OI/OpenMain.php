<?php
namespace Pages\Controllers\OI;

use AF\Util\OIML;
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

    private function sets($type){
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
        $this->sets(1);
    }

    public function generalpage(){
        $this->sets(2);
    }

    public function listpage(){
        $this->sets(3);
    }

    public function detailpage(){
        $this->sets(4);
    }

    public function userpage(){
        $this->sets(5);
    }

    public function searchpage(){
        $this->sets(6);
    }

    public function redirectings(){
        $this->sets(7);
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
