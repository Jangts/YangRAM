<?php
namespace Pages\Models\ViewModels\FE;

use CMF\Models\GEC;

class GeneralPage extends \Tangram\NIDO\DataObject {
    public function __construct($page, $params){
		if(isset($params->contentgroup)){
			$group = $params->contentgroup;
		}else{
			$this->error('NOTCCG');
		}
		if(isset($params->alias)){
			$alias = $params->alias;
		}else{
			$alias = NULL;
		}

		// var_dump($group, $alias);
		// var_dump($this->request, $alias);
		// die;
		if($content = GEC::byId($group, $alias)){
			$this->data = [
				str_replace('{@cg}', $content->GROUPCODE, str_replace('{@ct}', $content->TITLE, $page->title)),
				str_replace('{@cg}', $content->GROUPCODE, str_replace('{@ck}', $content->KEYWORDS, $page->keywords)),
				str_replace('{@cg}', $content->GROUPCODE, str_replace('{@cd}', $content->DESCRIPTION, $page->description)),
				$content,
				'state'	=>	true
			];
		}else{
			$this->data = ['state'	=>	false];
		}
	}

	public function assign($renderer){
		list($title, $keywords, $description, $content) = $this->data;
		$renderer->assign("__Title", $title);
		$renderer->assign("__KeyWords", $keywords);
		$renderer->assign("__Desc", $description);
		$renderer->assign("___TITLE", $content->TITLE);
		$renderer->assign("___GROUPCODE", $content->GROUPCODE);
		$renderer->assign("___ALIAS", $content->ALIAS);
		$renderer->assign("___BANNER", $content->BANNER);
		$renderer->assign("___KEY_COUNT", $content->KEY_COUNT);
		$renderer->assign("___CONTENT", $content->CONTENT);
		$renderer->assign("___Content", stripslashes(htmlspecialchars_decode($content->CONTENT)));
		$renderer->assign("___CUSTOM_I", $content->CUSTOM_I);
		$renderer->assign("___CUSTOM_II", $content->CUSTOM_II);
		$content->view();
	}
}