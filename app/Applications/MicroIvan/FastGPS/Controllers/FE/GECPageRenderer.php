<?php
namespace GPS\Controllers\FE;

use Status;
use AF\Models\Util\THMI;
use GPS\Models\Data\Page;

class GECPageRenderer extends \Controller {
    use traits;
    
	public function get($mark = '', $article = ''){
        if($mark){
            if($article){
                $page = Page::byFeature(3, $mark);
                if(!$page||!$page->KEY_STATE){
                    $page = new Page;
                    $default_theme = THMI::getDefault(AI_CURR);
                    if($default_theme){
                        $theme = $default_theme->alias;
                        if(is_file(PATH_VIEW.$theme.'/nimls/page/'.$mark.'/'.$article.'.niml')){
                            $pagename = 'Detail For Page ' . strtoupper($mark.'/'.$article);
                            $template = [$theme, 'page/'.$mark.'/'.$article.'.niml'];
                        }elseif(is_file(PATH_VIEW.$theme.'/nimls/page/'.$mark.'.niml')){
                            $pagename = 'Detail For Group ' . strtoupper($mark);
                            $template = [$theme, 'page/'.$mark.'.niml'];
                        }elseif(is_file(PATH_VIEW.$theme.'/nimls/page/default.niml')){
                            $template = [$theme, 'page/default.niml'];
                        }else{
                            $template = ['fast-gps-defaults', 'page.niml'];
                        }
                    }else{
                        $template = ['fast-gps-defaults', 'page.niml'];
                    }
                    
                    $page->put([
                        'type'				=>	3,
                        'mark'				=>	$mark,
                        'name'				=>	empty($pagename) ? 'New Page' : $pagename,
                        'title'				=>	'{@ct} - Microivan FastGPS',
                        'keywords'			=>	'{@ck} - {@cg}',
                        'description'		=>	'{@cd} - {@cg}',
                        'theme'				=>	$template[0],
                        'template'			=>	$template[1],
                        'KEY_STATE'		    =>	1
                    ]);
                    if(isset($pagename)){
                        $page->save();
                    }
                }
                return $this->commonPage('GPS\Models\ViewModels\FE\GeneralPage', $page, $article, ['_g_'.$mark.'_', $article]);
            }else{
                $page = Page::byFeature(2, $mark);
                if($page&&$page->KEY_STATE){
			        if($page->gec_default_alias){
                        return $this->get($mark, $page->gec_default_alias);
                    }
                    return $this->singlePage($page, ['_g_'.$mark.'_']);
		        }
            }
        }
        $this->moveto('p/404.html');
	}
}
