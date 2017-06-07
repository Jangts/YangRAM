<?php
namespace System\CACH;

/**
 *	General Data Storage
 *	一般数据存储仓
 *  数据存储对象，对应硬盘中的一个文件夹单位，通过“前缀+键值+后缀”来操作所存储的数据
 */
class Storage {
	use traits\filesys;

	const
	STR = 0,
	NUM = 1,
    SER = 2,
	JSN = 3,
	
	USE_FILE = 0,
	USE_MEMORY = 1,
	USE_REDIS = 2,
	USE_MEMCA = 3;

	protected
	$encodedMode, $cachePath, $isArray,
	$prefix = 'ca_',
	$suffix = '.ni',
    $useHashKey = true,
    $emptyEnable = false;

	public function __construct($mainPath, $encodedMode = Storage::JSN, $isArray = false){
		$this->cachePath = $mainPath;
		$this->encodedMode = $encodedMode;
		$this->isArray = !!$isArray;
	}

	public function setBefore ($dir = ''){
		$this->prefix = (string)$dir;
		return $this;
	}

	public function useHashKey ($use = true){
		$this->useHashKey = !!$use;
		return $this;
	}

	public function setAfter ($suffix = ''){
		$this->suffix = (string)$suffix;
		return $this;
	}

	public function check($index){
		$filename = $this->filename($index);
		return  is_file($filename);
	}

	public function take($index){
		$filename = $this->filename($index);
		if(is_file($filename)){
			return $this->decode(self::getContent($filename));
		}
		return false;
	}

	public function read($index){
		return $this->take($index);
	}

	public function filename($index){
        if($this->useHashKey){
            return $this->cachePath.$this->prefix.hash('md4', $index).$this->suffix;
        }
        return $this->cachePath.$this->prefix.$index.$this->suffix;
    }

	public function time($index){
		$filename = $this->filename($index);
		return filemtime($filename);
	}

	public function store($index, $value = false){
		$filename = $this->filename($index);
		if(is_bool($value)){
			if($value===false){
				self::deleteFile($filename);
			}
		}else{
			self::writeFile($filename, $this->encode($value));
		}
		return $this;
	}

	public function write($index, $value){
		return $this->store($index, $value);
	}

	public function get($index){
        return new StorageSegment($this, $index);
		return new RedisSegment($this, $index);
		return new MemcacheSegment($this, $index);
    }

	public function put($ss){
        # code
    }

	public function size($index = NULL){
		if($index){
			$filename = $this->filename($index);
			return self::getSize($filename);
		}
        return self::getPathSize($this->cachePath);
    }

	public function cleanOut(){
        if($this->emptyEnable&&is_dir($this->cachePath)){
            self::clearPath($this->cachePath);
            return true;
        }
        return false;
	}
}
