<?php
namespace Studio\Pub\Controllers;

use CM\GEC;
use CM\SPC;
use CM\SPCLite;

class Submitter extends \OIC\BaseOISubmitter {
	public function sav(){
		$post = $this->request->FORM;
		$this->saveContent(false, $post);
	}
	public function pub(){
		$post = $this->request->FORM;
		$this->saveContent(true, $post);
	}
	
	public function rmv(){
		$post = $this->request->FORM;
		$this->remvContent($post);
	}
	
	private function saveContent($publishing, $post){
		// var_dump($post);
        // die;
		$post = $post->stopAttack()->toArray();
		// var_dump($post);
        // die;
		if(isset($post['SET_ALIAS'])&&isset($post['ID'])){
			if($post['SET_ALIAS']=='general'){
				if($post['ID']=='new'){
					$obj = GEC::create($post);
					if($obj&&$id = $obj->ID){
						echo $id;
					}else{
						echo '<ERROR>';
					}
				}else{
					$obj = GEC::identity($post['ID']);
					if($obj->put($post)->save()){
						echo $post['ID'];
					}else{
						echo '<ERROR>';
					}
				}
			}else{
				if($publishing){
					$post["KEY_STATE"] = '1';
				}else{
					$post["KEY_STATE"] = '0';
				}
				if($post['ID']=='new'){
					$obj = SPC::create($post);
					if($obj&&$id = $obj->ID){
						echo $id;
					}else{
						echo '<ERROR>';
					}
				}else{
					$obj = SPC::byId($post['ID']);
					if($obj->put($post)->save()){
						echo $post['ID'];
					}else{
						echo '<ERROR>';
					}
				}
			}
		}
	}
	
	private function remvContent($post){
		$post = $post->toArray();
		if(isset($post['SET_ALIAS'])&&isset($post['ID'])){
			if($post['SET_ALIAS']=='general'){
				if(count(GEC::remove("ID = '".$post['ID']."'", 1))){
					echo $post['ID'];
				}else{
					echo '<ERROR>';
				}
			}else{
				if(count(SPCLite::remove("ID = '".$post['ID']."'", 1))){
					echo $post['ID'];
				}else{
					echo '<ERROR>';
				}
			}
		}
	}
}