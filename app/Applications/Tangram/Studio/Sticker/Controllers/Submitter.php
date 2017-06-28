<?php
namespace Studio\Stk\Controllers;

use CMF\Models\EMC;


class Submitter extends \OIC\OISubmitter_BC {
	public function save(){
		$post = $this->request->FORM;
		if(isset($post->id)){
			if($post->id=='new'){
				if($obj = EMC::post($post->toArray())){
					exit($obj->id);
				}
			}else{
				if($obj = EMC::byId($post->id)){
					$obj->put($post->toArray());
					$obj->KEY_MTIME = DATETIME;
					if($obj->save()){
						exit($obj->id);
					}
				}
			}
		}
		exit('<Error>');
	}

	public function remove(){
		$post = $this->request->FORM;
		if(isset($post->id)){
			if(count(EMC::remove("`id` = '$post->id'", 1))){
				exit($post->id);
			}
		}
		exit('<Error>');
	}
}