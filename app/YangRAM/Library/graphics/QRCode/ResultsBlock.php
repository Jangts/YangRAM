<?php
namespace Library\graphics\QRCode;

class ResultsBlock {
    public $dataLength;
    public $data = [];
    public $eccLength;
    public $ecc = [];
    
    public function __construct($dl, $data, $el, &$ecc, ResultsItem $rs){
        $rs->encode_rs_char($data, $ecc);
    
        $this->dataLength = $dl;
        $this->data = $data;
        $this->eccLength = $el;
        $this->ecc = $ecc;
    }
}