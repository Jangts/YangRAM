<?php
namespace AF\ResourceHolders;

use Status;
use Request;
use Application;
use AF\Controllers\IPC\Sender;

/**
 *	Uniform Application IPCommunicator
 *	统一应用消息收发器
 *  子应用间交互时消息收发验核的接口
 */
abstract class IPCommunicator_BaseClass {
    protected
	$tokenname = 'IPCTOKEN',
	$apis = [];

    public $sender;

    final public function __construct(Application $app, Request $request, array $options){
        $this->sender = new Sender($app, $request);
        $this->sender->tokenname = $this->tokenname;
    }

    final public function config($appid, $instr, $method = 'get'){
        $sender = $this->sender;
        if(isset($this->apis[$instr])){
            $num = func_num_args();
            $api = $this->apis[$instr];
            if($num>=$api['argc']+3){
                if(strtolower($method)==='set'){
                    $sender->url = __SET_DIR;
                }else{
                    $sender->url = __GET_DIR;
                }
                $args = array_slice(func_get_args(), 3);
                $sender->url .= $appid.'/'.$api['classalias'].'/'.$api['methodname'].'/'.join('/', $args).'/';
                $sender->result = 'Configured';
            }else{
                $sender->result = 'FewParameters';
            }
        }
        return $sender;
    }
}