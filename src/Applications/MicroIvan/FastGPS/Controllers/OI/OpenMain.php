<?php
namespace GPS\Controllers\OI;

use AF\ViewRenderers\OIML;
use GPS\Models\Data\LocalDict;
use GPS\Models\ViewModels\OI\StartPage;
use GPS\Models\ViewModels\OI\PageList;
use GPS\Models\ViewModels\OI\Form;

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

    public function index(){
        $this->list(2);
    }

    public function generalpage(){
        $this->list(3);
    }

    public function commonlist(){
        $this->list(4);
    }

    public function ataglist(){
        $this->list(5);
    }

    public function acatlist(){
        $this->list(6);
    }

    public function commondetail(){
        $this->list(7);
    }

    public function acatdetail(){
        $this->list(8);
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
