<?php
namespace GPS\Controllers\FE;

use Status;
use Response;
use System\NIDO\Guest;
use Packages\tplengines\Niml;
use AF\Models\Ect\SYSI;

trait traits {
	private $niml = NULL;

	private function assign($renderer, $col_tree){
		$sys = new SYSI;
		$sys = $sys->toArray();
		foreach($sys as $key => $val){
			$renderer->assign('__SYS_'.$key, $val);
		}
		$column = $this->request->COLUMN;
		$this->updateColumn($column, $col_tree);
		$renderer->assign('__Column', $column->__COL_ALIAS);
		$renderer->assign('__Columns', $column->__COL_TREE);
		$gst = new Guest;
		$gst->record($column->id());
	}

	private function updateColumn($column, $col_tree){
		foreach($col_tree as $col_alisa){
			$column->push($col_alisa);
		}
	}

    private function singlePage($page, $col_tree){
		$renderer = new Niml();
		$this->assign($renderer, $col_tree);

		$renderer->assign("__Title", $page->title);
		$renderer->assign("__KeyWords", $page->keywords);
		$renderer->assign("__Desc", $page->description);
		
		$page->countit();
		$renderer->using($page->theme);
		$renderer->display($page->template);
	}

	private function commonPage($classname, $page, $article, $col_tree){
		$pageview = new $classname($page, $article, $this->request->PARAMS);
		if($pageview->state){
			$renderer = new Niml();
			$this->assign($renderer, $col_tree);
			$pageview->assign($renderer, $page);

			$page->countit();
			$renderer->using($page->theme);
			$renderer->display($page->template);
		}
		$this->moveto('p/404.html');
	}

	private function moveto(){
		Response::moveto($this->request->REST_HANDLER_DIR.'p/404.html');
	}
}
