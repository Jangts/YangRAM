<?php
namespace AF\Controllers\VISA;

/**
 *	Common Member Authentication Abstract
 *	通用会员验证器抽象
 */
abstract class VisaWithAuthorizationCode_BC extends VISACtrller_BC {
    const       TYPE    =   'AUTHEN';
    protected   $type   =   'AUTHEN';

    abstract public function register();

    abstract public function checkPin($PIN);

    abstract public function myCall();

    abstract public function myTitle();
}
