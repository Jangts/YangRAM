<?php
namespace OIC;
use Response;
use Library\formattings\Timer;

abstract class I4PlazaWidgets_BaseClass extends BaseOICtrller {
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