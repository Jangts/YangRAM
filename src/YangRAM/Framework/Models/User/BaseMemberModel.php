<?php
namespace AF\Models\User;

use Model;

/**
 *	Application Member Information Model
 *	应用会员信息模型
 */
abstract class BaseMemberModel extends \AF\Models\BaseModel {
    protected
    $type = 0,
    $data = ['uid' => 0];

    final public function __construct($appid, $uid = 0){
        $this->appid = $appid;
        if($data = $this->query($uid)){
            $this->data = $data;
        }
        $this->__init();
    }

    protected function __init(){}

    abstract protected function query($uid);

    protected function build($data){

    }

    private function register(){

    }

    public function checkPin(){

    }
}
