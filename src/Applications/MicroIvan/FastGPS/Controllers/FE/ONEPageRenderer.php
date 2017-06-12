<?php
namespace GPS\Controllers\FE;

use Status;
use GPS\Models\Data\Page;

class ONEPageRenderer extends \Controller {
    use traits;
    
	public function get($mark = '', $article = ''){
        if($mark){
            $page = Page::byFeature(1, $mark);
            if($page&&$page->KEY_STATE){
			    return $this->singlePage($page, ['_p_'.$mark.'_']);
		    }else{
                $page = new Page;
                $page->put([
                    'type'				=>	1,
                    'mark'				=>	$mark,
                    'name'				=>	'New Page',
                    'title'				=>	'Page Not Found - Microivan FastGPS',
                    'theme'				=>	'fast-gps-defaults',
                    'template'			=>	'404.niml',
                    'KEY_STATE'		=>	1
                ]);
                return $this->singlePage($page, ['_p_404_']);
            }
        }
        $this->moveto('p/404.html');
	}
}
