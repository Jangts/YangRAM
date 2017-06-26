<?php
namespace UOI\Controllers;

use Status;
use RDO;
use Response;
use Application;
use Library\compliers\JSMin;
use Library\graphics\ImagePrinter;

class Applications extends \OIC\OICtrller_BC {
	public function AppRankingList(){
		$response = Response::instance('200');
		$response->MIME = 'application/javascript';
		$content = $this->buildDockAppsList();;
		if(_USE_DEBUG_MODE_){
			$response->send($content);
		}else{
			$response->send(JSMin::minify($content));
		}
	}

	public function buildDockAppsList(){
		if(($qrs = $this->queryDockAppsList())){
			return json_encode($qrs->toArray());
		}
		return '[]';
	}

	private function queryDockAppsList(){
		$rdo = new RDO;
		return $rdo->using(DB_SYS.'apps')
			->where('app_is_ondock', 1)
			//->where('app_id', 3, '>')
			->orderby('app_is_new', true)
			->orderby('app_count', true)
			->orderby('app_releasetime', true)
			->select([
				'app_id AS id',
				'app_name AS name',
				'app_code AS code',
				'app_icon AS icon',
				'app_bgcolor AS bgcolor',
				'app_installpath AS path',
				'app_authorname AS author',
				'app_releasetime AS date',
				'app_is_new AS isnew'
			]);
	}

	public function icoReader($appid, $size = 80, $linktype = 'normal'){
		$app = new Application($appid);
		if($size=='links'){
			return $this->linkIconReader($app, $linktype);
		}
		
		if(isset($app->Props['Icons']['normal'])&&is_file($path = $app->Path.'Sources/'.$app->Props['Icons']['normal'])){
			$imgsrc = $path;
		}elseif(is_file($path = $app->Path.'Sources/icon.png')){
			$imgsrc = $path;
		}else{
			$imgsrc = PATH_NIAF.'Sources/icon.png';
		}
		if(is_numeric($size)&&$size!=80){
			ImagePrinter::PNG($imgsrc, $size, $size, 80, 80);
		}else{
			ImagePrinter::PNG($imgsrc);
		}
	}

	public function linkIconReader($app, $linktype = 'normal'){
		if(isset($app->Props['Icons'][$linktype])&&is_file($imgsrc = $app->Path.'Sources/'.$app->Props['Icons'][$linktype])){
			ImagePrinter::PNG($imgsrc);
		}else{
			Status::notFound();
		}
	}
}
