<?php
namespace AF\Controllers\IPC;
use Tangram\IDEA;
use Status;
use Tangram\R5\RemoteDataReader as RemoteReader;
use Request;
use Application;

/**
 *	Inter-Process Communication Controller
 *	通用应用数据控制器
 *  控制器的基类，提供了控制器的基本属性和方法
 */
final class Sender extends CCBase {
    private static function collectTrashyToken(){
        if(abs(rand(0, _SESIION_DIVISOR_ * 2) - _SESIION_DIVISOR_)<=_SESIION_PROBAB_){
            $messages = glob(PATH_CACA.'[0-Z]*/message/*');
            $count = count($messages);
            foreach($messages as $message){
                $time = filemtime($message);
                if(time() - $time >= _WORKER_BUILD_TIMEOUT_){
                    unlink($message);
                }
            }
        }
    }

    public $tokenname = 'IPCTOKEN', $url, $params, $result;

    private function buildRequestToken(){
        self::collectTrashyToken();
        self::$storage->setBefore('tokens/');
        $token = hash('md4', AI_CURR.'->'.$this->app->APPID.'_'.microtime().uniqid());
        while(self::$storage->check($token)){
            $token = hash('md4', AI_CURR.'->'.$this->app->APPID.'_'.microtime().uniqid());
        }
        self::$storage->store($token, AI_CURR.'->'.$this->app->APPID);
        $this->params[$this->tokenname] = $token;
        return $token;
    }

    public function request(array $params = [], $form = false, $timeout = 30){
        $this->params = $params;
        $token = $this->buildRequestToken();
        if(is_array($form)){
            $key = $this->buffer($postarray, true);
            $this->params['_local_post_'] = $key;
        }
        $msg = $this->send($timeout, $token);
        self::$storage->store($token, false);
        if(isset($msg['type'])&&$msg['type']==='IPC_MSG'){
            return $this->checkMessage($msg, $reader);
        }
        return false;
    }

    private function send($timeout, $token){
        $ua = SPRINTF("YangRAM/%.1f (%s; %s; PHP %s) IPCommunicator/%s ",
        IDEA::VERSION,PHP_OS,$_SERVER['SERVER_SOFTWARE'],
        PHP_VERSION,
        IDEA::VERSION.'.'.IDEA::DEVID);
		$reader = new RemoteReader($this->url, $this->params, RemoteReader::JSN, []);
        return $reader->setAgent($ua)->setTimeout($timeout)->read()->toArray();
    }

    private function checkMessage(array $msg, RemoteReader $reader){
        if(isset($msg['msg'])){
            if(isset($msg['key'])){
                if(self::$storage->setBefore('params/')->check($msg['key'])){
                    $this->result = [
                        'msg'   =>  $msg['msg'],
                        'data'  =>  self::$storage->take($msg['key'])
                    ];
                    self::$storage->store($msg['key'], false);
                }
            }else{
                $this->result = $msg['msg'];
            }
        }elseif(isset($msg['key'])){
            if(self::$storage->setBefore('params/')->check($msg['key'])){
                $this->result = self::$storage->take($msg['key']);
                self::$storage->store($msg['key'], false);
            }
        }else{
            $this->result = $reader->toArray();
        }
        return true;
    }
}
