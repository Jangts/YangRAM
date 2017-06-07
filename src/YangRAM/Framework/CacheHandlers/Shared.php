<?php
namespace AF\CacheHandlers;
use Storage;

/**
 *	Application Open Data Storage
 *	应用开放数据存储仓
 *  用来存放应用共享数据的数据存储仓
 *  其接口被设计为文件的读写形式，而不是加密模式，以保证文件名的所见及所得
 */
class Shared extends Storage {
    protected $appid;

	public function __construct($appid = AI_CURR){
		$this->appid  = $appid;
        $this->cachePath = PATH_ODAT.$appid.'/';
	}

	public function whose(){
		return $this->appid;
	}

	public function filename($filename){
        return $this->cachePath.$index;
    }
	
	public function get($filename){
		return file_get_contents($this-$filename($filename));
	}

	public function put($filename, $text, $append){
		if($append){
			return file_put_contents($this-$filename($filename), $text, FILE_APPEND);
		}
		return file_put_contents($this-$filename($filename), $text);
	}
}