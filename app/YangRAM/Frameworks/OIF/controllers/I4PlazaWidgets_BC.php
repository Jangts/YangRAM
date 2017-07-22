<?php
namespace OIF\controllers;
use Response;
use Library\formattings\Timer;

abstract class I4PlazaWidgets_BC extends OICtrller_BC {
	protected static function format($data){
		Response::instance(200, Response::JS)->send(json_encode($data));
	}

    protected function loaderLocalTimer($lang = '') {
		if(empty($lang)){
			$lang = _LANG_;
		}
		$this->timer = new Timer($lang);
	}
}