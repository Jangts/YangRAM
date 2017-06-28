<?php
namespace Tangram\CACHE;
/**
 *	General Data Storage Segment
 *	一般数据存储单元
 *  一般数据存储仓的单元数据对象
 */
final class StorageSegment {
	use \Tangram\NIDO\traits\formatting;
	use traits\filesys;

	protected
	$encodedMode, $filename, $isArray;

	public function __construct(Storage $storage, $index){
		$this->filename = $storage->filename($index);
		$this->encodedMode = $storage->encodedMode();
		$this->isArray = $storage->isArray();
		$this->cachePath();
	}

	protected function cachePath(){
		if (!file_exists($path = dirname($this->filename))){
			mkdir($path, 0777, true) or die("Unable create cache directory!");
		}
	}

	public function getFile(){
		return $this->filename;
	}

	public function getTime(){
		if(is_file($this->filename)){
			return filemtime($this->filename);
		}
		return false;
	}

	public function toString(){
    	return self::getContent($this->filename);
	}

	public function read(){
		return $this->decode($this->toString());
	}

	public function sRead($timeout){
		$timeout = intval($timeout);
		if($this->getTime()+$timeout>=time()){
			return $this->decode($this->toString());
		}
		return false;
	}

	public function toArray(){
        return self::getArray($this->read());
    }

    public function toJson(){
		if($this->encodedMode==='json'){
			return $this->toString();
		}
		return self::getJson($this->read());
    }

	public function toXml(){
		if($this->xml){
            return $this->xml->outputMemory(true);
        }
        return self::getXmlbyArray($this->read());
	}

	public function write($value){
		self::writeFile($this->filename, $this->encode($value));
		return $value;
	}
}
