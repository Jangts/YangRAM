<?php
namespace AF\Models\Certificates;

use SESS;
use RDO;
use Request;
use Tangram\NIDO\UserAccount;
use Tangram\NIDO\Guest;

/**
 *	General User Passport
 *	通用用户护照
 *  单例类
 *	用户身份认证处理的全局对象
 */
final class StdPassport extends Passport_BC {
    const
    QUERY_LESS = -1,
    QUERY_ALL = 0,
    QUERY_UID = 1,
    QUERY_USERNAME = 2,
    QUERY_EMAIL = 3,
    QUERY_MOBILEPHONE = 4,
    QUERY_UNICODENAME = 5;

    private static
    $uid =0,
    $inited = false,
    $instance = NULL;

	public static function instance($sid=null){
		if(self::$instance === NULL){
            SESS::init($sid);
			self::$instance = new self();
		}
		return self::$instance;
	}

    public static function whose(){
		return self::$uid;
	}

    private
    $conn = NULL,
    $checked = false,
    $accountsTable = DB_SYS.'users',
    $walletsTable = DB_APP.'users_wallets',
    $isRegistered = false,
    $authentication = NULL,
    // $certificate = [
    //     'USER'              =>  NULL,
    //     'READABLE'          =>  false,
    //     'PRIVACY_READ'      =>  false,
    //     'REVISABLE'         =>  false,
    //     'PAYABLE'           =>  false,
    //     'POSITIVE_CTRL'     =>  false,
    // ],
    $account = NULL,
    $guest = NULL;

    private function __construct(){
        $this->conn = new RDO;
        $this->conn->using($this->accountsTable);
        RDO::stopAttack(QS_SCAN_COOKIE);
        $this->check();
    }

    private function check(){
        $REQUEST = Request::instance();
        if(defined('_TEST_MODE_')&&_TEST_MODE_&&isset($REQUEST->ARGS->t_signas)){
            $username = sprintf('%x', strtolower($REQUEST->ARGS->t_signas));
            $this->account = UserAccount($username, StdPassport::QUERY_UID);
            StdPassport::$uid = $this->account->uid;
            $this->guest = new Guest();
            if($this->account->uid){
                $this->setUserPower($this->account);
            }
            $this->checked = true;
        }

        if($this->checked===false){
            if(isset($_SESSION['username'])){
                // 检查是否为读权登陆
                $username = $_SESSION['username'];
                // if(isset($_COOKIE["plenipotentiary"])&&$_COOKIE["plenipotentiary"]===$username){
                //     // 检查是否为全权登陆
                //     if(isset($_SESSION["HASHx3"])){
                //         return $this->recheckPower($_SESSION["plenipotentiary"], $_SESSION["HASHx3"]);
                //     }else{
                //         return $this->abstain();
                //     }
                // }elseif(isset($_COOKIE["revisor"])&&$_COOKIE["revisor"]===$username){
                //     // 检查是否为写权登陆
                //     $this->certificate['REVISABLE'] = true;
                // }
                // $this->certificate['USER'] = $username;
                // $this->certificate['READABLE'] = true;
                // $this->certificate['PRIVACY_READ'] = true;
                $_SESSION['username'] = $username;
                setcookie('username', $username, time()+_COOKIE_EXPIRY_, '/', HOST, _USE_HTTPS_, true);
            }elseif(isset($_COOKIE['username'])){
                // 检查是否为印象登陆
                $username = strtolower($_COOKIE['username']);
                // $this->certificate['USER'] = $username;
                // $this->certificate['READABLE'] = true;
                setcookie('username', $username, time()+_COOKIE_EXPIRY_, '/', HOST, _USE_HTTPS_, true);
            }else{
                $username = null;
            }
            $this->account = new UserAccount($username);
            StdPassport::$uid = $this->account->uid;
            $this->guest = new Guest();
            $this->checked = true;
        }
    }

    public function checkLoginPassword($identification, $password, $method = 2){
        // HiYangR7M
        if($identification){
            switch($method){
                case 0:
                $account = self::chechByAL($identification, $password);
                break;
                case 1:
                $account = self::chechById($identification, $password);
                break;
                case 2:
                $account = self::chechByUN($identification, $password);
                break;
                case 3:
                $account = self::chechByEM($identification, $password);
                break;
                case 4:
                $account = self::chechByMP($identification, $password);
                break;
                case 5:
                $account = self::chechByUC($identification, $password);
                break;
            }
            if($account){
                $this->account = new UserAccount($account, StdPassport::QUERY_LESS);
                StdPassport::$uid = $this->account->uid;
                $this->guest = new Guest($account['uid']);
                $this->setUserPower($account);
                return $this->checked = true;;
            }
        }
        return false;
    }

    private function setUserPower($account){
        // $this->certificate['USER'] = $account['username'];
        // $this->certificate['READABLE'] = true;
        // $this->certificate['PRIVACY_READ'] = true;
        // $this->certificate['REVISABLE'] = true;
        $_SESSION['username'] = $account['username'];
        setcookie('username', $account['username'], time()+_COOKIE_EXPIRY_, '/', HOST, _USE_HTTPS_, true);
        setcookie("revisor", $account['username'], time()+_COOKIE_REVISOR_EXPIRY_, '/', HOST, _USE_HTTPS_, true);
    }

    // public function getFullPower($authorization_code){
    //     if($this->certificate['USER']){
    //         $this->conn->where("`username` = '$this->certificate['USER']' AND `authorization_code` = '".hash('sha1', md5(hash('sha256', $authorization_code)))."'");
    //         if($account = $this->conn->select()){
    //             return $this->setFullPower($account);
    //         }else{
    //             return $this->abstain();
    //         }
    //     }
    // }

    public function checkPaymentPassword($currency, $pw_pay){
        if($uid = System::$passport->uid){
            $conn = new RDO;
            $conn->using($this->walletsTable)
                 ->where('uid', $uid)
                 ->where('currency', $currency)
                 ->where('pw_pay', $pw_pay);

            if(($conn->select())){
                $this->certificate['READABLE'] = true;
                $this->certificate['PRIVACY_READ'] = true;
                $this->certificate['PAYABLE'] = true;
                return true;
            }
            return $this->abstain();
        }
    }

    // private function setFullPower($account){
    //     $this->certificate['USER'] = $account['username'];
    //     $this->certificate['READABLE'] = true;
    //     $this->certificate['PRIVACY_READ'] = true;
    //     $this->certificate['REVISABLE'] = true;
    //     $this->certificate['POSITIVE_CTRL'] = true;
    //     $_SESSION['username'] = $account['username'];
    //     $_SESSION["HASHx3"] = $account['authorization_code'];
    //     setcookie('username', $account['username'], time()+_COOKIE_EXPIRY_, '/', HOST, _USE_HTTPS_, true);
    //     setcookie("revisor", $account['username'], time()+_COOKIE_REVISOR_EXPIRY_, '/', HOST, _USE_HTTPS_, true);
    //     setcookie("plenipotentiary", $account['username'], time()+_COOKIE_EXPIRY_, '/', HOST, _USE_HTTPS_, true);
    //     return true;
    // }

    public function abstain(){
        // $this->certificate['READABLE'] = false;
        // $this->certificate['PRIVACY_READ'] = false;
        // $this->certificate['REVISABLE'] = false;
        // $this->certificate['PAYABLE'] = false;
        // $this->certificate['POSITIVE_CTRL'] = false;
        session_destroy();
        setcookie('username', NULL, time()-1, '/', HOST, _USE_HTTPS_, true);
        setcookie("revisor", NULL, time()-1, '/', HOST, _USE_HTTPS_, true);
        setcookie("plenipotentiary", NULL, time()-1, '/', HOST, _USE_HTTPS_, true);
        return false;
    }

    // private function recheckPower($username, $HASHx3){
    //     $this->conn->where("`username` = '$username'");
    //     if(($account = $this->conn->select()&&($account['authorization_code'])===$HASHx3)){
    //         return $this->setFullPower();
    //     }else{
    //         return $this->abstain();
    //     }
    // }

    private function chechById($uid, $password){
        if($tsr = $this->conn->where("`uid` = '$uid' AND `password` = '".md5(hash('sha256', $password))."'")->select()){
            return $tsr->getRow();
        }
        return false;
    }

    private function chechByUN($username, $password){
        $tsr = $this->conn->where('username', $username)->where('password', md5(hash('sha256', $password)))->select();
        if($tsr&&($row=$tsr->getRow())){
            return $row;
        }
        return false;
    }

    private function chechByEM($email, $password){
        if($tsr = $this->conn->where("`email` = '$email' AND `password` = ".md5(hash('sha256', $password))."'")->select()){
            return $tsr->getRow();
        }
        return false;
    }

    private function chechByMP($mobile, $password){
        if($tsr = $this->conn->where("`mobile` = '$mobile' AND `password` = '".md5(hash('sha256', $password))."'")->select()){
            return $tsr->getRow();
        }
        return false;
    }

    private function chechByUC($unicodename, $password){
        if($tsr = $this->conn->where("`unicodename` = '$unicodename' AND `password` = '".md5(hash('sha256', $password))."'")->select()){
            return $tsr->getRow();
        }
        return false;
    }

    public function __get($property){
        $account = $this->account->toArray();
        if(isset($account[$property])){
            return $account[$property];
        }
        $guest = $this->guest->toArray();
        if(isset($guest[$property])){
            return $guest[$property];
        }
        // if(isset($this->certificate[$property])){
        //     return $this->certificate[$property];
        // }
        return NULL;
    }

    public function recordGuest(){
        return $this->guest->record();
    }
}
