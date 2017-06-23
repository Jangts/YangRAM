<?php
namespace Pages\Models\ViewModels\FE;

use Library\ect\Pager;
use CM\SPC\Preset;
use CM\SPC\Category;
use CM\SPC;

class DetailPage extends \Tangram\NIDO\DataObject {
    public function __construct($page, $params){
		$preset_alias = $params->preset;
		$item_id = $params->item;

		if(($content = SPC::byId($item_id))&&($content->SET_ALIAS==$preset_alias)){
			$page->countit();

			$preset = Preset::alias($preset_alias);
			$category_id = $content->CAT_ID;
			$category = Category::identity($category_id);
			if($category){
				$cat = $category->title;
				$cak = $category->keywords;
				$cad = $category->description;
			}else{
				$cat = '';
				$cak = '';
				$cad = '';
			}
			if($content->CONTENT){
				$array = preg_split('/\{\{\@page_break\}\}/i', $content->CONTENT);
				$count = count($array);
				if($count>1){
					$pi = is_numeric($params->page) ? $params->page : 1;
					$pi = $pi > $count ? $count : $pi;
					$pi --;
					$pi = $pi < 0 ? 0 : $pi;
					$content->CONTENT = $array[$pi];
					$cpage = $pi + 1;
				}else{
					$cpage = 1;
				}
				Pager::config('COUNT', $count);
				Pager::config('CPAGE', $cpage);
				Pager::config('PRE', 1);
			}else{
				$cpage = 1;
			}
			$this->data = [
				str_replace('{@cpn}', $preset->name, str_replace('{@cat}', $cat,str_replace('{@ct}', $content->TITLE, $page->title))),
				str_replace('{@cpn}', $preset->name, str_replace('{@cak}', $cak, str_replace('{@ck}', $content->KEYWORDS, $page->keywords))),
				str_replace('{@cpn}', $preset->name, str_replace('{@cad}', $cad, str_replace('{@cd}', $content->DESCRIPTION, $page->description))),
				$preset_alias, $category_id, $preset, $category, $content, $cpage,
				'state'	=>	true
			];
		}else{
			$this->data = ['state'	=>	false];
		}
	}

	public function assign($renderer, $page){
		list($title, $keywords, $description, $preset_alias, $category_id, $preset, $category, $content, $cpage) = $this->data;
	    $renderer->assign("__Title", $title);
		$renderer->assign("__KeyWords", $keywords);
		$renderer->assign("__Desc", $description);
		$renderer->assign('___PRESET_ALIAS', $preset_alias);
		$renderer->assign('___CATEGORY_ID', $category_id);
		$renderer->assign('___PRESET', $preset);
		$renderer->assign('___CATEGORY', $category);
        $renderer->assign($content->toArray(), '___');
		if($page->use_context){
			$renderer->assign($content->contexts());
		}
		$renderer->assign("___Cpage", $cpage);
        if($preset->basic_type="ablm"){
			if(!$array = json_decode(htmlspecialchars_decode($content->IMAGES), true)){
				$array = [];
			}
			$renderer->assign('___IMAGES_TO_ARRAY', $array);
		}
	}
}