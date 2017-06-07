<?php
namespace AF\CacheHandlers;
use Storage;

/**
 *	Application Data Storage
 *	应用数据存储仓
 *  争对子应用专门拓展的数据存储对象
 *  保证每一款子应用独享的磁盘目录PATH_CACA下的一个文件夹
 */
final class AppCache extends Storage {
    protected
	$appid,
	$isArray,
	$useHashKey = false,
    $encodedMode = 1;

	public function __construct($appid = 'Common', $isArray = true, $encodedMode = Storage::JSN, $suffix = '.ni'){
		$this->appid  = strtoupper($appid);
        $this->cachePath = PATH_CACA.$appid.'/';
        $this->isArray = ($isArray != false);
		$this->encodedMode = $encodedMode;
		$this->suffix = $suffix;
	}

    public function whose(){
		return $this->appid;
	}
}
