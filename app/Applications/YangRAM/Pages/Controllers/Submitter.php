<?php
namespace Pages\Controllers;

use Library\formattings\ScalarFormat;
use Pages\Models\Data\Page;

class Submitter extends \Controller {	
	public function save(){
		$post = $this->request->FORM;
		$post = $post->toArray();
		if($post['id']=='0'){
			$page = new Page;
			$page->type = 1;
		}else{
			if(empty($post['id'])){ 
				exit('<ERROR>');
			}
			$page = Page::byId($post['id']);
		}
		if($page){
			foreach ($post as $key => $val ){
				$post[$key] = htmlspecialchars(ScalarFormat::chrUnescape($val), ENT_QUOTES);
			}
			$post["KEY_MTIME"] = DATETIME;
			if($page->put($post)->save()){
				exit($page->pid);
			}
			exit('<ERROR>');
		}
		exit('<CAN_NOT_FIND>');
	}
	
	public function del(){
		$post = $this->request->FORM;
		if(empty($post->id)){
			exit('<ERROR>');
		}
		$page = Page::byId($post->id);
		if($page){
			$page->KEY_IS_RECYCLED = 1;
			$page->KEY_MTIME = DATETIME;
			if($page->save()){
				exit($page->pid);
			}
			exit('<ERROR>');
		}
		exit('<CAN_NOT_FIND>');
	}
	
	public function disure(){
		$post = $this->request->FORM;
		if(empty($post->id)){
			exit('<ERROR>');
		}
		$page = Page::byId($post->id);
		if($page){
			$page->KEY_STATE = 0;
			$page->KEY_MTIME = DATETIME;
			if($page->save()){
				exit($page->pid);
			}
			exit('<ERROR>');
		}
		exit('<CAN_NOT_FIND>');
	}
	
	public function use(){
		$post = $this->request->FORM;
		if(empty($post->id)){
			exit('<ERROR>');
		}
		$page = Page::byId($post->id);
		if($page){
			$page->KEY_STATE = 1;
			$page->KEY_MTIME = DATETIME;
			if($page->save()){
				exit($page->pid);
			}
			exit('<ERROR>');
		}
		exit('<CAN_NOT_FIND>');
	}
}