<?php
namespace AF\Controllers\VISA;

/**
 *	Common Identification Abstract
 *	通用身份识别器抽象
 */
abstract class VisaByCustomCertificate_BC extends VISACtrller_BC {
    const       TYPE    =   'IDENTT';
    protected   $type   =   'IDENTT';

    abstract protected function init();

    abstract public function activate();

    abstract public function myCall();

    abstract public function myPoints();

    abstract public function myTitle();
}
