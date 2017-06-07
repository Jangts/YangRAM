<?php
namespace UOI\Controllers;

use AF\Models\User\OIF\Operator;

final class VISA extends \OIC\OperatorVISACtrller {
    public function logondesktop(){
        $post = $this->request->FORM;
        $username = $post->username;
        $password = $post->password;
        $pin = $post->pin;
        if($username&&$password&&$pin){
            $checked = $this->passport->checkLoginPassword($username, $password);
            if($checked){
                $this->uid = $this->passport->uid;
                $this->member = new Operator($this->aid, $this->uid);
                $this->checkPinCode($pin);
                if($this->status==='Runholder'){
                    echo '[{"username":"'.$username.'","getter_path":"'.__GET_DIR.'","session_id":"'.session_id().'","avatar":"'. __DIR.$this->member->AVATAR.'"}]';
                }else{
                    setcookie('operator', NULL, time()-1, '/', HOST, _USE_HTTPS_, true);
                    $this->passport->abstain();
                    echo '[{"error":"PIN_ERROR"}]';
                }
            }else{
                $this->passport->abstain();
                echo '[{"error":"ACCOUNT_ERROR"}]';
            }
        }else{
            echo '[{"error":"INPUTS_ERROR"}]';
        }
    }
}
