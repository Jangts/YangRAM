<?php
namespace System\CACH;

/**
 *	User Files Storage
 *	用户文件存储器
 *	负责将不同用户的文件按照用户名存到响应的用户文件夹内
 */
final class UserFiles {
	use traits\filesys;

	private
	$username, $cachePath,
	$encodedMode = Storage::JSN,
	$isArray = true;

	private function write($filename, $value){
		$path = dirname($filename);
		if (!file_exists($path)){
			mkdir($path, 0777, true);
		}
		if(is_bool($value)){
			if($value===false){
				self::deleteFile($filename);
			}
		}else{
			self::writeFile($filename, $this->encode($value));
		}
		return $this;
	}

	public function __construct($username = 'Public'){
		$this->username  = $username;
		$this->cachePath  = PATH_USR.'_'.$username.'/';
	}

	public function whoes(){
		return $this->username;
	}

	public function readUserData($type = 'baseinfo'){
		$filename = $this->cachePath.'UserData/ca_'.hash('md4', $type).'.ni';
		return $this->r($filename);
	}

	public function readAppCache($app_id, $index){
		$filename = $this->cachePath.'AppCache/'.$app_id.'/ca_'.hash('md4', $index).'.ni';
		return $this->r($filename);
	}

	public function readCache($index, $time = 0){
		$filename = $this->cachePath.'Cache/ca_'.hash('md4', $index).'.ni';
		return $this->r($filename);
	}

	private function r($filename){
		$content = self::getContent($filename);
		if($content===false){
			return false;
    	}
    	return $this->decode($content);
	}

	public function writeUserData($type = 'baseinfo', $value){
		$filename = $this->cachePath.'UserData/ca_'.hash('md4', $type).'.ni';
		return $this->write($filename, $value);
	}

	public function writeAppCache($app_id, $index, $value){
		$filename = $this->cachePath.'AppCache/'.$app_id.'/ca_'.hash('md4', $index).'.ni';
		return $this->w($filename, $value);
	}

	public function writeCache($index, $value){
		$filename = $this->cachePath.'Cache/ca_'.hash('md4', $index).'.ni';
		return $this->w($filename, $value);
	}

	public function w($filename, $value){
		if($value===false){
			return self::deleteFile($filename);
		}
		self::writeFile($filename, $this->encode($value));
		return $value;
	}
}