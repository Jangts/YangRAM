<?php
namespace AF\Controllers\IPC;

use Status;
use Storage;
use Request;
use Application;

/**
 *	Inter-Process Communication Controller
 *	通用应用数据控制器
 *  控制器的基类，提供了控制器的基本属性和方法
 */
abstract class CommunicationController_BC extends \AF\Controllers\Controller_BC {
    use methods;

    protected static $storage = null;

    protected $tokenname = 'IPCTOKEN';

    final protected static function initStorage($appid){
        if(self::$storage===null){
            self::$storage = new Storage(PATH_CACA.$appid.'/message/', Storage::SER, true);
            self::$storage->useHashKey(false)->setAfter();
        }
    }

    final private function prepareReturenMessage(array $data, $message = ''){
        if($key = $this->buffer($data, false)){
            return [
                'type'  => 'IPC_MSG',
                'key'   =>  $key,
                'msg'   =>  $message
            ];
        }
        return [
            'type'  => 'IPC_MSG',
            'msg'   =>  'DATA_RETURN_ERROR'
        ];
    }

    protected $is_run_in_background = 0;

    public function __construct(Application $app, Request $request){
        $this->request = $request;
        $this->app = $app;
        self::initStorage($app->APPID);
	}

    final protected function runInBackground(){
        if($this->is_run_in_background===0){
            ignore_user_abort(true);
            set_time_limit(0);
            $this->is_run_in_background = 1;
        }
        return true;
    }

    final protected function buffer(array $data, $post = false){
        if($post){
            self::$storage->setBefore('puts/');
        }else{
            self::$storage->setBefore('gets/');
        }
        $key = hash('md4', AI_CURR.'->'.$this->app->APPID.'_'.microtime().uniqid());
        while(self::$storage->check($key)){
            $key = hash('md4', AI_CURR.'->'.$this->app->APPID.'_'.microtime().uniqid());
        }
        self::$storage->store($key, $data);
        return $key;
    }

    final protected function response($message = '', $data = false){
        $msg = [];
        if(is_array($message)){
            $msg = $this->prepareReturenMessage($message);
        }else if(is_string($message)){
            if(is_array($data)){
                $msg = $this->prepareReturenMessage($data, $message);
            }else{
                $msg = [
                    'type'  => 'IPC_MSG',
                    'msg'   =>  $message
                ];
            }
        }
        header('Content-Length: Content-Type: application/x-javascript');
        echo json_encode($msg);
    }

    final protected function closeConnection($message = '', $data = false) {
        $this->runInBackground();
        $this->response($message, $data);
        $length = ob_get_length();
        header('Content-Length: Content-Type: application/x-javascript');
        header('Content-Length: '. $length);
        header('Connection: close');
        ob_flush();
        flush();
    }
}
