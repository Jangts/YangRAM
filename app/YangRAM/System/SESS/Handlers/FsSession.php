<?php
namespace Tangram\SESS\Handlers;
use Status;

/**
 *	File Session
 *	文档存储SESSION解决方案
 */
final class FsSession implements \Tangram\SESS\NI_Session_interface {
    private static
    $instance = NULL,
    $savePath = PATH_CAC_SES;

	public static function instance(){
		if(FsSession::$instance===NULL){
			FsSession::$instance = new self;
		}
        return FsSession::$instance;
	}

	private function __construct(){}

    public function open($savePath, $sessionName) {
        if (!is_dir(self::$savePath)) {
            mkdir(self::$savePath, 0777, true);
        }
		return true;
    }

    public function close() {
        return true;
    }

    public function read($id) {
        $file = self::$savePath."/sess_$id";
        if (is_file($file) && ((filemtime($file) + _SESIION_EXPIRY_) >= time())) {
            return (string)(@file_get_contents($file));
        }
        return '';
    }

    public function write($id, $data) {
        if(empty($data)){
            return true;
        }
        return file_put_contents(self::$savePath."/sess_$id", $data) === false ? false : true;
    }

    public function destroy($id) {
        $file = self::$savePath."/sess_$id";
        if (is_file($file)&&unlink($file)) {
            return true;
        }
        return false;
    }

    public function gc($maxlifetime){
        foreach (glob(self::$savePath."/sess_*") as $file) {
            if (is_file($file) && ((filemtime($file) + _SESIION_EXPIRY_) < time())) {
                unlink($file);
            }
        }
        return true;
    }

	public function __clone(){
		throw new \Exception('Cannot Copy Object!');
	}
}
