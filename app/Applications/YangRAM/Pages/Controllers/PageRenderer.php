<?php
namespace Pages\Controllers;

use Status;
use Response;
use Tangram\NIDO\Guest;


use Packages\niml\Niml;
use AF\Models\Util\SYSI;

use Pages\Models\Data\UserInfo;
use Pages\Models\Data\Page;

use Pages\Models\ViewModels\FE\SearchPage;



/**
 * 通用页面渲染类
 * 页面类型：
 * * 1 * 独立页面，又叫专题页面，页面内容不随参数变化而变化
 * * 2 * 一般页面，又叫系列页面，页面内容通过[内容组参数]与通用内容组关联，使用同一个模板，通过不同的[内容别名参数]来显示不同的内容
 * * 3 * 列表页面，页面内容通过[预设别名参数]与预设的专用内容集合关联
 * * 4 * 内容页面，又叫详情页面，页面内容通过[预设别名参数]和[内容标识参数]与专用内容单元关联
 * * 5 * 会员页面，输出会员信息页面，根据参数的不同，控制内容的可见性，以及输出各类不同的内容
 * * * * 提供如下 5 种可见性方案供选，若要实现更多复杂组合，建议使用前端技术进行判断后选择性请求加载，或者另建具有征对性的渲染应用
 * * * * 5 * 仅自己可见
 * * * * 4 * 粉丝可见
 * * * * 3 * 关注的可见
 * * * * 2 * 好友可见（互相关注即为好友）
 * * * * 1 * 会员可见
 * * * * 0 * 开放页面
 * * * * 另外，根据会员页面的定义，登陆页面、注册页面等不属于会员页面，理应用独立页面或一般页面完成
 * * * * 且根据YangRAM的接口规范，YangRAM并不允许使用Custom_Router接口提交数据，所有数据必须通过Setter接口，即便强行使用开放式会员页面渲染了此类页面，最终还是要异步提交到UserSystem或其他有注册功能的应用的注册接口中去
 * * 6 * 搜索页面，展示通用内容和专用内容的搜索结果的页面
 * * 7 * 外链页面，又叫跳转页面，是通过301方式跳转到外部的页面，其优点是可以按规律批量配置并内部统计 
 */

class PageRenderer extends \Controller {
	private
	$pages = NULL,
	$niml = NULL;

	private function error($code){
		$page = Page::byId(404);
		if($page){

		}else{
			$status = new Status(404);
			$status->write('PAGES_RENDERDER_ERROR_'.$code);
			return $status->cast(Status::CAST_PAGE, Status::TEMP_404);
		}
	}

	private function assign($renderer){
		$sys = new SYSI;
		$sys = $sys->toArray();
		foreach($sys as $key => $val){
			$renderer->assign('__SYS_'.$key, $val);
		}
		$usr = new UserInfo;
		$usr = $usr->toArray();
		foreach($usr as $key => $val){
			$renderer->assign('__User_'.$key, $val);
		}
		$column = $this->request->COLUMN;
		$renderer->assign('__Column', $column->__COL_ALIAS);
		$renderer->assign('__Columns', $column->__COL_TREE);
		$gst = new Guest;
		$gst->record($column->id());
	}

	public function main($pid){
		$this->page = Page::byId($pid);
		if($this->page&&$this->page->KEY_STATE){
			//$this->niml = new Niml();
			switch ($this->page->type) {
				case 1:
				return $this->singlePage();
				case 2:
				return $this->commonPage('Pages\Models\ViewModels\FE\GeneralPage');
				case 3:
				return $this->commonPage('Pages\Models\ViewModels\FE\ListPage');
				case 4:
				return $this->commonPage('Pages\Models\ViewModels\FE\DetailPage');
				case 5:
				return $this->userPage();
				case 6:
				return $this->searchPage();
				case 7:
				return $this->jumpPage();
			}
		}
		$this->error('NOPAGE['.$pid.']');
	}

	private function singlePage(){
		$renderer = new Niml();
		$this->assign($renderer);

		$page = $this->page;
		$renderer->assign("__Title", $page->title);
		$renderer->assign("__KeyWords", $page->keywords);
		$renderer->assign("__Desc", $page->description);
		
		$page->countit();
		$renderer->using($page->theme);
		$renderer->display($page->template);
	}

	private function commonPage($classname){
		$page = $this->page;
		$pageview = new $classname($page, $this->request->PARAMS);
		if($pageview->state){
			$renderer = new Niml();
			$this->assign($renderer);
			$pageview->assign($renderer, $page);

			$page->countit();
			$renderer->using($page->theme);
			$renderer->display($page->template);
		}else{
			$this->error('NOGEC');
		}
	}

	private function searchPage(){
		$page = $this->page;
		$searchPage = new SearchPage($page, $this->request->PARAMS, $page->query_key);
		
		$renderer = new Niml();
		$this->assign($renderer);
		$searchPage->assign($renderer);

		$page->countit();
		$renderer->using($page->theme);
		if($searchPage->state){
			$renderer->display($page->template);
		}else{
			if(empty($page->initial)){
				$renderer->display($page->template);
			}else{
				$renderer->display($page->initial);
			}
		}
	}

	private function jumpPage(){
		$url = $this->page->url;
		$rq = $this->request->PARAMS->rq;
		if(isset($rq)){
			$url = str_replace("&lt;REQUEST&gt;", $rq, $url);
		}
		Response::moveto($url);
	}
}
