<?php
namespace GPS\Controllers\FE;

use Status;
use AF\Models\Ect\THMI;
use GPS\Models\Data\Page;

class SPCPageRenderer extends \Controller {
    use traits;
    
	public function get($mark = ''){
        $params = $this->request->PARAMS;
        if($mark){
            if($params->article){
                if($params->category){
                    $col_tree = ['_s_'.$mark.'_', 'category'.$params->category];
                    $page = Page::byFeature(8, $mark);
                }else{
                    $col_tree = ['_s_'.$mark.'_'];
                    $page = Page::byFeature(7, $mark);
                }
                if(!$page||!$page->KEY_STATE){
                    $page = new Page;
                    $default_theme = THMI::getDefault(AI_CURR);
                    if($default_theme){
                        $theme = $default_theme->alias;
                        $type = 7;
                        if(($params->category!==null)&&is_file(PATH_VIEW.$theme.'/nimls/detail/'.$mark.'/'.$params->category.'.niml')){
                            $pagename = 'Detail For Category ' . $params->category;
                            $type = 8;
                            $template = [$theme, 'detail/'.$mark.'/'.$params->category.'.niml'];
                        }elseif(is_file(PATH_VIEW.$theme.'/nimls/detail/'.$mark.'.niml')){
                            $pagename = 'Detail For ' . strtoupper($mark);
                            $template = [$theme, 'detail/'.$mark.'.niml'];
                        }elseif(is_file(PATH_VIEW.$theme.'/nimls/detail/default.niml')){
                            $template = [$theme, 'detail/default.niml'];
                        }else{
                            $template = ['fast-gps-defaults', 'detail.niml'];
                        }
                    }else{
                        $template = ['fast-gps-defaults', 'detail.niml'];
                    }
                    $page->put([
                        'type'				=>	$type,
                        'mark'				=>	$mark,
                        'name'				=>	empty($pagename) ? 'New Page' : $pagename,
                        'title'				=>	'{@ct} - Microivan FastGPS',
                        'keywords'			=>	'{@ck}',
                        'description'		=>	'{@cd}',
                        'use_base64'		=>	1,
                        'use_context'		=>	1,
                        'theme'				=>	$template[0],
                        'template'			=>	$template[1],
                        'KEY_STATE'		    =>	1
                    ]);
                    if(isset($pagename)){
                        $page->save();
                    }
                }
                return $this->commonPage('GPS\Models\ViewModels\FE\DetailPage', $page, $params->article, $col_tree);
            }else{
                if($params->category){
                    $col_tree = ['_s_'.$mark.'_', 'category'.$params->category];
                    $page = Page::byFeature(6, $mark);
                }elseif($params->tag){
                    $col_tree = ['_s_'.$mark.'_'];
                    $page = Page::byFeature(5, $mark);
                }else{
                    $col_tree = ['_s_'.$mark.'_'];
                    $page = Page::byFeature(4, $mark);
                }
                if(!$page||!$page->KEY_STATE){
                    $page = new Page;
                    $default_theme = THMI::getDefault(AI_CURR);
                    $type = 4;
                    if($default_theme){
                        $theme = $default_theme->alias;
                        if(($params->category!==null)&&is_file(PATH_VIEW.$theme.'/nimls/list/'.$mark.'/'.$params->category.'.niml')){
                            $pagename = 'List For Category ' . $params->category;
                            $type = 6;
                            $template = [$theme, 'list/'.$mark.'/'.$params->category.'.niml'];
                        }elseif(is_file(PATH_VIEW.$theme.'/nimls/list/'.$mark.'.niml')){
                            $pagename = 'List For ' . strtoupper($mark);
                            $template = [$theme, 'list/'.$mark.'.niml'];
                        }elseif(is_file(PATH_VIEW.$theme.'/nimls/list/default.niml')){
                            $template = [$theme, 'list/default.niml'];
                        }else{
                            $template = ['fast-gps-defaults', 'list.niml'];
                        }
                    }else{
                        $template = ['fast-gps-defaults', 'list.niml'];
                    }
                    $page->put([
                        'type'				=>	$type,
                        'mark'				=>	$mark,
                        'name'				=>	empty($pagename) ? 'New Page' : $pagename,
                        'title'				=>	'{@type} - Microivan FastGPS Listpage',
                        'keywords'			=>	'{@type} - Microivan FastGPS Listpage',
                        'description'		=>	'{@type} - Microivan FastGPS Listpage',
                        'sort_order'		=>	3,
                        'prepage_number'	=>	8,
                        'theme'				=>	$template[0],
                        'template'			=>	$template[1],
                        'KEY_STATE'		    =>	1
                    ]);
                    if(isset($pagename)){
                        $page->save();
                    }
                }
                return $this->commonPage('GPS\Models\ViewModels\FE\ListPage', $page, $params->category, $col_tree);
            }
        }
        $this->moveto('p/404.html');
	}
}
