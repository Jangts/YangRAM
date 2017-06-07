<?php
namespace Pages\Models\ViewModels\FE;

use System\NIDO\DataObject;
use CM\SPC\Preset;
use CM\SPC\Category;
use CM\SPCLite;
use Library\ect\SearchEngine;
use Library\ect\Pager;


class SearchPage extends DataObject {
    private static $orderby = [
		 1	=>	SPCLite::ID_DESC,
		 2	=>	SPCLite::ID_ASC,			
		 3	=>	[['PUBTIME', true, DataObject::SORT_REGULAR], ['ID', true, DataObject::SORT_REGULAR]],
		 4	=>	SPCLite::PUBTIME_ASC,
		 5	=>	[['KEY_MTIME', true, DataObject::SORT_REGULAR], ['ID', true, DataObject::SORT_REGULAR]],
		 6	=>	SPCLite::MTIME_ASC,
		 7	=>	[['KEY_COUNT', true, DataObject::SORT_REGULAR], ['ID', true, DataObject::SORT_REGULAR]],
		 8	=>	SPCLite::HIT_ASC,
		 9	=>	[['RANK', true, DataObject::SORT_REGULAR], ['ID', true, DataObject::SORT_REGULAR]],
		10	=>	SPCLite::RANK_ASC,
		11	=>	SPCLite::TITLE_DESC_GBK,
		12	=>	SPCLite::TITLE_ASC_GBK,
	];

    private $rule = [
		'Item' => 'Contents',
		'Table' => 'ni_cnt_in_special_use',
		'Index' => 'ID',
		'Title' => 'TITLE',
		'Desc' => 'DESCRIPTION',
		'Fields' => 'TITLE, DESCRIPTION',
		'Order' => [['PUBTIME', true, DataObject::SORT_REGULAR], ['ID', true, DataObject::SORT_REGULAR]],
		'Remark' => NULL,
	];

	public function __construct($page, $params, $key){
		if(empty(trim($params->$key))){
			$this->data = ['', $page->title, $page->prepage_number, 1, [], 0, [], 'state'	=>	false];
		}else{
            return $this->search($page, $params, $params->$key);
		}
	}

	public function assign($renderer){
		list($kw, $suffix, $prepage, $currpage, $items, $count, $kwarr) = $this->data;
		$renderer->assign("__Title", $kw . $suffix);
		$renderer->assign("__KeyWords", '');
		$renderer->assign("__Desc", '');
		$renderer->assign("__Presets", Preset::all(true));
		$renderer->assign("__Categories", Category::all());

		$renderer->assign("___KEYWORD", $kw);
        $renderer->assign("___PrevPage", $prepage);
        $renderer->assign("___Cpage", $currpage);
        $renderer->assign("___LIST", $items);
		$renderer->assign("___TOTAL", $count);
        $renderer->assign("___WORDS", $kwarr);
	}

    private function search($page, $params, $kw){
		if(isset($params->preset)){
			$preset = $params->preset;
			if(isset($params->cat)){
				$cat = $params->cat;
				if($params->cat>0){
					$this->rule["Auxiliary"] .= " AND CAT_ID = '$cat'";
				}else{
					$this->rule["Auxiliary"] .= " AND SET_ALIAS = '$preset' AND CAT_ID = '$cat'";
				}
			}else{
				$this->rule["Auxiliary"] .= " AND SET_ALIAS = '$preset'";
			}
		}else{
			if(isset($params->cat)&&$params->cat>0){
				$this->rule["Auxiliary"] .= " AND CAT_ID = '$cat'";
			}
		}
		if(isset($params->sort)){
			$this->rule["Order"] = self::$orderby[$params->sort];
		}elseif(isset($page->sort_order)){
			$this->rule["Order"] = self::$orderby[$page->sort_order];
		}

		if($page->preset_alias){
			$preset_alias = preg_split('/\s*,\s*/', $page->preset_alias);
		}else{
			$presets = Preset::all(true);
			$preset_alias = [];
			foreach($presets as $preset){
				$preset_alias[] = $preset->alias;
			}
		}
		
		$this->rule['Auxiliary'] = "KEY_IS_RECYCLED = 0 AND KEY_STATE = 1 AND SET_ALIAS IN ('" . join("', '", $preset_alias) . "')";	
		
		$engine = new SearchEngine($this->rule);

		$engine->search($kw);

		if(isset($params->page)){
			$currpage = $params->page;	
		}else{
			$currpage = 1;
		}
		$take = $page->take($params->page);
        

		$items = $engine->getRS();
		$count = count($items);
		$items = array_slice($items, $take[1], $take[3]);
	
		Pager::config(array(
				'CURR'		=>	$currpage,
				'COUNT'		=>	$count,
				'PRE'		=>	$page->prepage_number,
			)
		);

		$this->data = [
            $kw,
            $page->gap_symbol . $page->title,
            $page->prepage_number,
            $currpage,
            $items,
            $count,
            $engine->getKeyWords(),
            'state'	=>	true
        ];
	}
}