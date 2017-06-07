<?php
namespace GPS\Controllers\FE;

use Status;
use GPS\Models\Data\Page;

class GECPageRenderer extends \Controller {
    use traits;
    
	public function get($mark = '', $article = ''){
        if($mark){
            if($article){
                $page = Page::byFeature(3, $mark);
                if(!$page||!$page->KEY_STATE){
                    $page = new Page;
                    $page->put([
                        'type'				=>	0,
                        'mark'				=>	'',
                        'name'				=>	'New Page',
                        'title'				=>	'{@ct} - Microivan FastGPS',
                        'keywords'			=>	'{@ck} - {@cg}',
                        'description'		=>	'{@cd} - {@cg}',
                        'theme'				=>	'fast-gps-defaults',
                        'template'			=>	'details.niml',
                        'KEY_STATE'		    =>	1
                    ]);
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
