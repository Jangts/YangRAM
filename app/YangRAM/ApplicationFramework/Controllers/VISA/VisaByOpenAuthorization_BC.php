<?php
namespace AF\Controllers\VISA;

/**
 *	Common Member Authentication Abstract
 *	通用会员验证器抽象
 */
abstract class VisaByOpenAuthorization_BC extends VISACtrller_BC {
    const       TYPE    =   'AUTHEN';
    protected   $type   =   'AUTHEN';

    abstract protected function init();

    abstract public function register();

    abstract public function checkPin($PIN);

    abstract public function myCall();

    abstract public function myPoints();

    abstract public function myTitle();
}
