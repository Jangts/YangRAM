<?php
namespace Pages\Models\ViewModels\FE;

use Library\ect\Pager;
use CM\SPC\Preset;
use CM\SPC\Category;
use CM\SPCLite;
use CM\SPC;

class ListPage extends \System\NIDO\DataObject {
	public function __construct($page, $params){
		$preset_alias = $params->preset;
		$category_id = $params->category;
		$tag = $params->tag;

		# 需要使用Page自带的方法计算排序和选取范围
		# 用到的参数为Page的sort_order和prepage_number
		# 可能需要的参数为Parameters的page
		$order = $page->orderby();
		$take = $page->take($params->page);

		if(is_numeric($category_id)){
			if($category_id){
				if($tag){
					 $contents = SPC::byTag($tag, $category_id, $order, $take[1]);
					 Pager::config('COUNT', SPCLite::countbyTag($tag, $category_id));
				}else{
					$contents = SPC::getList($preset_alias, $category_id, SPCLite::PUBLISHED, $order, $take[1], $take[3]);
					Pager::config('COUNT', SPC::count($preset_alias, $category_id, SPCLite::PUBLISHED));
				}
				$category = Category::identity($category_id);
				$preset = Preset::id($category->set_id);
				$preset_alias = $preset->alias;
				$title = str_replace('{@cat}', $category->title, $page->title);
				$keywords = str_replace('{@cak}', $category->keywords, $page->keywords);
				$description = str_replace('{@cad}', $category->description, $page->description);
			}elseif(is_string($preset_alias)){
				if($tag){
					 $contents = SPC::byTag($tag, $preset_alias, $order, $take[1]);
					 Pager::config('COUNT', SPCLite::countbyTag($tag, $preset_alias));
				}else{
					$contents = SPC::getList($preset_alias, 0, SPCLite::PUBLISHED, $order, $take[1], $take[3]);
					Pager::config('COUNT', SPC::count($preset_alias, 0, SPCLite::PUBLISHED));
				}
				$preset = Preset::alias($preset_alias);
				$category = Category::identity(0);
				$category_id = 0;
				$title = str_replace('{@cat}', '', $page->title);
				$keywords = str_replace('{@cak}', '', $page->keywords);
				$description = str_replace('{@cad}', '', $page->description);
			}
			$this->data = [
				str_replace('{@type}', $preset->name, $title),
				str_replace('{@type}', $preset->name, $keywords),
				str_replace('{@type}', $preset->name, $description),
				$take, $preset_alias, $category_id, $preset, $category, $contents,
				'state'	=>	true
			];
		}elseif(is_string($preset_alias)){
			if($tag){
				$contents = SPC::byTag($tag, $preset_alias, $order, $take[1]);
				Pager::config('COUNT', SPCLite::countbyTag($tag,$preset_alias));
			}else{
				$contents = SPC::getList($preset_alias, NULL, SPCLite::PUBLISHED, $order, $take[1], $take[3]);
				Pager::config('COUNT', SPC::count($preset_alias, NULL, SPCLite::PUBLISHED));
			}
			$preset = Preset::alias($preset_alias);
			$category = NULL;
			$category_id = NULL;
			$title = str_replace('{@cat}', $preset->name, $page->title);
			$keywords = str_replace('{@cak}', $preset->name, $page->keywords);
			$description = str_replace('{@cad}', $preset->name, $page->description);
			$this->data = [
				str_replace('{@type}', $preset->name, $title),
				str_replace('{@type}', $preset->name, $keywords),
				str_replace('{@type}', $preset->name, $description),
				$take, $preset_alias, $category_id, $preset, $category, $contents,
				'state'	=>	true
			];
		}else{
			$this->data = ['state'	=>	false];
		}
	}

	public function assign($renderer){
		list($title, $keywords, $description, $take, $preset_alias, $category_id, $preset, $category, $contents) = $this->data;
		$renderer->assign("__Title", $title);
		$renderer->assign("__KeyWords", $keywords);
		$renderer->assign("__Desc", $description);
		$renderer->assign("___Cpage", $take[0]);
		$renderer->assign("___StartIndex", $take[1]);
		$renderer->assign("___EndIndex", $take[2]);
		$renderer->assign("___PrevPage", $take[3]);
		$renderer->assign('___PRESET_ALIAS', $preset_alias);
		$renderer->assign('___CATEGORY_ID', $category_id);
		$renderer->assign('___PRESET', $preset);
		$renderer->assign('___CATEGORY', $category);
		$renderer->assign('___LIST', $contents);
		$renderer->assign('___TOTAL', count($contents));
		Pager::config('CPAGE', $take[0]);
	}
}