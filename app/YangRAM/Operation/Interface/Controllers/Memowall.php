<?php
namespace UOI\Controllers;

use Response;
use UOI\Models\LinkGroup;

class Memowall extends \OIF\controllers\OICtrller_BC {
	public function marks(){
		$response = Response::instance(200, Response::JSON);
		$username = $this->passport->username;
		
		$filename = PATH_USR.'_'.$username.'/OperatorData/links.json';
		if(is_file($filename)&&$data = file_get_contents($filename)){
			$response->send($data);
		}
		$data = $this->buildJson($data);
		file_put_contents($filename, $data);
		$response->send($data);
	}
	
	private function buildJson($data){
		$groups = [];
		$group = new LinkGroup(['name' => 'Default']);
		$this->createItem($group, '1', 'DE', 'Launch YangRAM Explorer');
		$this->createItem($group, '2', 'Trash Can', 'Launch Trash Can');
		$this->createItem($group, '3', 'Setting', 'Launch Settings');
		$groups[] = $group->toJson();
		return '[' . join(',', $groups) . ']';
	}	

	private function createItem($group, $appid, $name, $desc){
		$group->addLink([
			'appid' 		=>	$appid,
			'icon' 			=>	__GET_DIR.'uoi/apps/icons/'.$appid.'/80/',
			'name'			=>	$name,
			'description' 	=>	$desc,
			'href'			=>	'default',
			'menu'			=>	'bookmark'
		]);
	}
}