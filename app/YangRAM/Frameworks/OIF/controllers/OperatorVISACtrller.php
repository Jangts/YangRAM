<?php
namespace OIF\controllers;
use RDO;
use Status;
use AF\Models\Certificates\StdPassport;
use OIF\models\Operator;

class OperatorVISACtrller extends \AF\Controllers\VISA\VisaWithAuthorizationCode_BC {

    protected function init(){
        $this->member = new Operator($this->aid, $this->uid);
        $this->checkStatus();
    }

    private function checkStatus(){
        //var_dump($this->appid, $this->member);
        if($this->member->UID){
            if(isset($_SESSION['operator'])&&($_SESSION['operator']===$this->member->OPERATORNAME)){
                $_SESSION['operator'] = $this->member->OPERATORNAME;
                setcookie('operator', $this->passport->username, time()+_COOKIE_EXPIRY_, '/', HOST, _USE_HTTPS_, true);
                setcookie('opavatar', $this->member->AVATAR, time()+_COOKIE_EXPIRY_, '/', HOST, _USE_HTTPS_, true);
                $this->status = 'Runholder';
            }elseif(isset($_COOKIE['operator'])&&($_COOKIE['operator']===$this->passport->username)){
                setcookie('operator', $this->passport->username, time()+_COOKIE_EXPIRY_, '/', HOST, _USE_HTTPS_, true);
                setcookie('opavatar', $this->member->AVATAR, time()+_COOKIE_EXPIRY_, '/', HOST, _USE_HTTPS_, true);
                $this->status = 'Member';
            }else{
                if($this->passport->PRIVACY_READ){
                    $this->status = 'Familiar';
                }else{
                    $this->status = 'Acquaintance';
                }
            }
        }else{
            $this->status = 'Guest';
        }
    }

    public function register(){}

    public function myGroup(){}

    public function checkPinCode($PIN){
        $rdo = new RDO;
        $rdos = $rdo->using(DB_AST.'uoi_operators')->where('UID', $this->member->UID)->where('PIN', $PIN)->select('UID');
        if($rdos&&$rdos->getCount()){
            $_SESSION['operator'] = $this->member->OPERATORNAME;
            $this->checkStatus();
        }else{
            unset($_SESSION['operator']);
        }
    }

    public function checkPin($pin){
        if($this->status==='Member'){
            $this->checkPinCode($pin);
            if($this->status==='Runholder'){
                $_SESSION['username'] = $this->passport->username;
                echo '[{"username":"'.$this->passport->username.'"}]';
            }else{
                echo '[{"error":"PIN_ERROR"}]';
            }
        }else{
            echo '[{"error":"LOG_AGAIN"}]';
        }
    }
    
    public function myCall(){}
    
    public function myPoints(){}
    
    public function myTitle(){}

    public function logon(){
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
                    echo '[{"username":"'.$username.'","avatar":"'. __DIR.$this->member->AVATAR.'"}]';
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
    
    public function logoff(){
        unset($_SESSION['operator']);
        setcookie('operator', NULL, time()-1, '/', HOST, _USE_HTTPS_, true);
        exit('[{"reply":"see-you"}]');
    }

    public function lock(){
        unset($_SESSION['operator']);
        exit('[{"reply":"see-you"}]');
    }
}
