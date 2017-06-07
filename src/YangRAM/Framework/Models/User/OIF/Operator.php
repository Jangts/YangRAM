<?php
namespace AF\Models\User\OIF;
use Storage;
use RDO;

final class Operator extends \AF\Models\User\BaseMemberModel {

    protected
    $data = [
        'UID'           =>  -1,
        'OPERATORNAME'  =>  'NonAuth User',
        'CAPTCHA'       =>  NULL,
        'OGROUP'        =>  0,
        'AVATAR'        =>  '/o/files/img/1ca28525a8b386236136.jpg',
        'LANGUAGE'      =>  REQUEST_LANGUAGE
    ];

    protected function __init(){
        if(empty($this->data['LANGUAGE'])&&is_string($this->data['LANGUAGE'])){
            $GLOBALS['RUNTIME']->LANGUAGE = $this->data['LANGUAGE'] = REQUEST_LANGUAGE;
        }else{
            $GLOBALS['RUNTIME']->LANGUAGE = $this->data['LANGUAGE'];
        }
    }

    protected function query($uid){
        //var_dump($this->appid, $uid);
        $cache = new Storage(PATH_CACA.'UOI/operators/', Storage::JSN, true);
        if($data = $cache->take($uid)){
            $this->data = $data;
        }else{
            $rdo = new RDO;
            $qs = $rdo->using(DB_AST.'uoi_operators')->where('UID', $uid)->select('UID, OPERATORNAME, CAPTCHA, OGROUP, AVATAR, LANGUAGE');
            if($qs&&$qs->getCount()){
                $this->data = $qs->getRow();
                $cache->store($uid, $this->data);
            }
        }
    }
}
