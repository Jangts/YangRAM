<?php
namespace Tangram\NIDO;

use RDO;
use AF\Models\Certificates\StdPassport;
use Tangram\CACH\UserFiles;

/**
 *	User Account
 *	用户账号对象
 *	新建、读取、删改用户账号信息的对象
 */
final class UserAccount extends DataObject {
    private
    $conn = NULL,
    $files = NULL,
    $registerable = true;

    protected
    $class = 'user',
    $databaseTable = DB_SYS.'users',
    $ignores = ['password', 'authorization_code'],
    $data = [
        'uid'           =>  0,
        'username'      =>  'guest',
        'nickname'      =>  NULL,
        'unicodename'   =>  NULL,
        'avatar'        =>  NULL,
        'email'         =>  '',
        'mobile'        =>  '',
        'remark'        =>  NULL,
        'regtime'       =>  NULL,
        'lasttime'      =>  ''
    ];
    
    public static function all(){
		#
		return false;
	}

    public function __construct($identification = NULL, $method = StdPassport::QUERY_USERNAME){
        if($identification){
            $this->conn = new RDO;
            $this->conn->using($this->databaseTable);
            switch($method){
                case -1:
                $account = $identification;
                break;
                case 1:
                $account = self::queryById($identification);
                break;
                case 2:
                $this->files = new UserFiles($identification);
                $account = self::queryByUN($identification);
                break;
                case 3:
                $account = self::queryByEM($identification);
                break;
                case 4:
                $account = self::queryByMP($identification);
                break;
                case 5:
                $account = self::queryByUC($identification);
                break;
            }
            if($account){
                $this->data = $account;
                $this->trim();
            }
        }
    }

    private function trim(){
        unset($this->data['password']);
        unset($this->data['authorization_code']);
    }

    private function read(){

    }

    private function write(){

    }

    private function queryById($uid){
        $this->conn->where('uid', $uid);
        return $this->conn->select()->getRow();
    }

    private function queryByUN($username){
        if($data = $this->files->readUserData('baseinfo')){
            return $data;
        }
        $this->conn->where('username', $username);
        $data = $this->conn->select()->getRow();
        $this->files->writeUserData('baseinfo', $data);
        return $data;
    }

    private function queryByEM($email){
        $this->conn->where('email', $email);
        return $this->conn->select()->getRow();
    }

    private function queryByMP($mobile){
        $this->conn->where('mobile', $mobile);
        return $this->conn->select()->getRow();
    }

    private function queryByUC($unicodename){
        $this->conn->where('unicodename', $unicodename);
        return $this->conn->select()->getRow();
    }
}
