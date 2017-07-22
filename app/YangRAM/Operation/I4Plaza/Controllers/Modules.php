<?php
namespace I4Plaza\Controllers;

use RDO;
use Response;
use Controller;

class Modules extends Controller {
	public function widgets(){
		//$data = RDO::arr($this->app->DBTPrefix.'wedgits', 'KEY_STATE = 1', 'sort ASC');
		//echo json_encode($data);
		$response = Response::instance('200');
		$response->MIME = Response::JSON;
		$response->send('[]');
	}
}
