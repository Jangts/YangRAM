<?php
namespace Studio\Pub\Controllers;

use AF\Models\Certificates\StdPassport;
use AF\Util\OIML;
use CMF\Models\GEC;
use CMF\Models\SPC\Preset;
use Studio\Pub\Models\LocalDict;
use Studio\Pub\Models\StartPage;
use Studio\Pub\Models\GECListPage;
use Studio\Pub\Models\SPCListPage;
use Studio\Pub\Models\SPCEditPage;

class OIViewController extends \Controller {
    public function main(){
		$localdict = LocalDict::instance();
		$oiml = new OIML;
		$oiml->assign('LOCAL', $localdict);
        $oiml->assign('PAGETITLE', $localdict->appname);
		$oiml->assign('LANG', $localdict->code());
		$oiml->display('default');
	}

	public function startpage(){
        $localdict = LocalDict::instance();
        $content = new StartPage($localdict);
        echo $content->render();
	}

    public function gec($preset = 'general', $id = NULL){
        $oiml = new OIML;
        $localdict = LocalDict::instance();
        $uriarr = $this->request->URI_PATH;
		$length = $this->request->LENGTH;
        $params = $this->request->PARAMS;
        if($id){
            $oiml->assign('LOCAL', $localdict);
            $oiml->assign('CID', $id);
            $oiml->assign('SORT', $params->sort);
            $oiml->assign('STTS', $params->stts);
            $oiml->assign('PAGE', $params->page);
            $oiml->assign('CLS', $params->cls);
            if(is_numeric($id)){
                if($content=GEC::identity($id)){
                    $oiml->assign('VALUES', GEC::identity($id)->toArray());
                }else{
                    #Error
                }
            }elseif($id==='new'){
                $oiml->assign('VALUES', (new GEC)->toArray());
            }else{
                #Error
            }
            $oiml->assign('ARGS', $this->gecargs($params, $id));
            $oiml->assign('UID', StdPassport::instance()->uid);
	        $oiml->display('gecform');
        }else{
            $data = new GECListPage($localdict, $uriarr, $length);
		    $oiml->assign('LOCAL', $localdict);
            $oiml->assign('SET_SIDE', $data->render('side'));
            $oiml->assign('SET_LIST', $data->render('list'));
            $oiml->assign('SET_PAGE', $data->render('page'));
		    $oiml->display('main');
        }
    }

    private function gecargs($params, $id){
        $params = $params->toArray();
		if(isset($params["sort"])){
			$args = 'general, '.$id.', '.$params["sort"];
		}else{
			$args = 'general, '.$id.', cd';
		}
		if(isset($params["page"])){
			$args .= ', '.$params["page"];
		}else{
			$args .= ', 1';
		}
		if(isset($params["group"])){
			$args .= ', '.$params["group"];
		}
        return $args;
    }

    public function spc($preset, $id = NULL){
        $oiml = new OIML;
        $localdict = LocalDict::instance();
        $uriarr = $this->request->URI_PATH;
		$length = $this->request->LENGTH;
        $params = $this->request->PARAMS;
        if($id){
            $args = $this->spcargs($params, $preset, $id);
            $data = new SPCEditPage($preset, $id, $args, $localdict);
		    $oiml->assign('LOCAL', $localdict);
            $oiml->assign('CID', $id);
            $oiml->assign('SORT', $params->sort);
            $oiml->assign('STTS', $params->stts);
            $oiml->assign('PAGE', $params->page);
            $oiml->assign('CLS', $params->cls);
            $oiml->assign('PREVIEW_THEME', $data->theme);
            $oiml->assign('PREVIEW_TEMPLATE', $data->template);
            $oiml->assign('PRIMER', $data->primer);
            $oiml->assign('VALUES', $data->content);
            $oiml->assign('TITLEDESC', $data->titleplaceholder);
            $oiml->assign('CUSTOMS', $data->inputs);
            $oiml->assign('CATS', $data->cats);
            $oiml->assign('ARGS', $data->args);
            $oiml->assign('UID', StdPassport::instance()->uid);
		    $oiml->display('spcform');
        }else{
            $presetinfo = Preset::alias($preset);
            if($presetinfo){
		        $oiml->assign('LOCAL', $localdict);
                $base = new SPCListPage\Params($presetinfo, $params, $uriarr, $length);
                $side = new SPCListPage\Menu($localdict, $presetinfo, $base);
                $list = new SPCListPage\Sheet($localdict, $presetinfo, $base);
                $page = new SPCListPage\Paging($localdict, $base);
                $oiml->assign('SET_SIDE', $side->render());
                $oiml->assign('SET_LIST', $list->render());
                $oiml->assign('SET_PAGE', $page->render());
		        $oiml->display('main');
            }else{
                $oiml->assign('LOCAL', $localdict);
                $oiml->display('404');
            }
        }
    }

    private function spcargs($params, $preset, $id){
        $params = $params->toArray();
        if(isset($params["sort"])){
            $args = $preset.', '.$id.', '.$params["sort"];
        }else{
            $args = $preset.', '.$id.', cd';
        }
        if(isset($params["page"])){
            $args .= ', '.$params["page"];
        }else{
            $args .= ', 1';
        }
        if(isset($params["stts"])){
            $args .= ', '.$params["stts"];
        }else{
            $args .= ', all';
        }
        if(isset($params["cat"])){
            $args .= ', '.$params["cat"];
        }else{
            $args .= ', null';
        }
        return $args;
    }
}
