<?php
namespace AF\Controllers\VISA;

/**
 *	Common Member Authentication Abstract
 *	通用会员验证器抽象
 */
abstract class VisaByAuthorizationCode_BC extends BaseVISACtrller {
    const       TYPE    =   'AUTHEN';
    protected   $type   =   'AUTHEN';

    abstract public function register();

    abstract public function checkPin($PIN);

    abstract public function myCall();

    abstract public function myTitle();
}
