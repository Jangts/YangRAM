<?php
namespace Explorer\Controllers;
use CM\SPC;
use CM\SRC;
use CM\SRCLite;
use CM\SRC\Folder;

class Submitter extends \Controller {
	public function mod_name(){
		$post = $this->request->FORM->toArray();
		if(isset($post["type"])){
			$type = $post["type"];
			if(isset($post["id"])&&isset($post["name"])&&strlen($post["name"])>0&&strlen($post["name"])<=50&&preg_match('/(<|>|\/|\\|\||:|\"|\*|\?)/', $post["name"], $matchs)==NULL){
				switch($type){
					case 'folder':
					return $this->modifyFolderName($post);
					
					case 'img':
					return $this->modifyResourceName('img');
					
					case 'txt':
					return $this->modifyResourceName('wav');
					
					case 'wav':
					return $this->modifyResourceName('wav');
					
					case 'vod':
					return $this->modifyResourceName('vod');
					
					case 'doc':
					return $this->modifyResourceName('doc');
					
				}
			}
		}
		exit('<ERROR>');
	}
	
	private function modifyFolderName($post){
		if($folder= Folder::modifyName($post["id"], $post["name"])){
			exit($folder->name);
		}
		exit('<ERROR>');
	}
	
	private function modifyResourceName($type){
		$post = $this->request->FORM->toArray();
		if(isset($post["id"])&&isset($post["name"])){
			$obj = new SRCLite($post["id"], $type);
			if($newName = $obj->rename($post["name"])){
				exit($newName);
			}
		}
		exit('<ERROR>');
	}
	
	public function new_folder(){
		$post = $this->request->FORM->toArray();
		if(isset($post["parent"])&&$post["parent"]>4){
			if(Folder::create($post["parent"])){
				exit('<SUCCESS>');
			}
			exit('<ERROR>');
		}
		exit('<NO_PERMISSIONS>');
	}
	
	public function rmv_folder(){
		$post = $this->request->FORM->toArray();
		if(isset($post["fldid"])){
			if(Folder::removeById($post["fldid"], SRCLite::RECYCLE)){
				exit('<SUCCESS>');
			}
			exit('<CAN_NOT_FIND>');
		}
		exit('<ERROR>');
	}
	
	public function rmv_content(){
		$post = $this->request->FORM->toArray();
		if(isset($post["id"])){
			$obj = SPC::byId($post["id"]);
			if($obj->recycle()){
				exit('<SUCCESS>');
			}
			exit('<CAN_NOT_FIND>');
		}
		exit('<ERROR>');
	}

    public function rmv_img(){
        $this->removeResource('img');
    }
    
    public function rmv_txt(){
        $this->removeResource('txt');
    }
	public function rmv_wav(){
        $this->removeResource('wav');
    }
	public function rmv_vod(){
        $this->removeResource('vod');
    }
	public function rmv_doc(){
        $this->removeResource('doc');
    }
	
	private function removeResource($type){
		$post = $this->request->FORM->toArray();
		if(isset($post["id"])){
			$obj = new SRCLite($post["id"], $type);
			if($obj->recycle()){
				exit('<SUCCESS>');
			}
			exit('<CAN_NOT_FIND>');
		}
		exit('<ERROR>');
	}
	
	public function move_to_folder(){
		$post = $this->request->FORM->toArray();
		if(isset($post["parent"])&&isset($post["type"])&&isset($post["id"])){
			if($post["type"]=='folder'){
				if($post["id"]==$post["parent"]){
					exit('<SELF>');
				}
				$obj = Folder::identity($post["id"]);
				$obj->parent = $post["parent"];
				if($result=$obj->save()){
					exit('<SUCCESS>');
				}
				if($result===0){
					exit('<CHILD>');
				}
				if($result===NULL){
					exit('<!EXIST>');
				}
				exit('<ERROR>');
			}else{
				$obj = new SRCLite($post["id"], $post["type"]);
				$obj->FLD_ID = $post["parent"];
				if($obj->save()){
					exit('<SUCCESS>');
				}
				exit('<ERROR>');
			}
		}
		exit('<ERROR>');
	}
}
