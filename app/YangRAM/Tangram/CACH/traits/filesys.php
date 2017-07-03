<?php
namespace Tangram\CACH\traits;

use Tangram\CACH\Storage;
/**
 *	File Operation Trait
 *	文件操作特性
 *  数据存储对象，对象硬盘中的一个文件夹单位，通过“前缀+键值+后缀”来操作所存储的数据
 */
trait filesys {
    public static function clearPath($path) {
	    $dh = opendir($path);
	    while ($file = readdir($dh)) {
	        if ($file != "." && $file != "..") {
	            $fullpath = $path."/".$file;
	            if (!is_dir($fullpath)) {
	                \unlink($fullpath);
	            } else {
	                self::clearPath($fullpath);
	            }
	        }
	    }
	    closedir($dh);
	}

    public static function getPathSize($path) {
        $handle = opendir($path);
        $sizeResult = 0;
        while (false !== ($FolderOrFile = readdir($handle))) {
            if ($FolderOrFile != "." && $FolderOrFile != "..") {
                if (is_dir("$path/$FolderOrFile")) {
                    $sizeResult += self::getDirSize("$path/$FolderOrFile");
                } else {
                    $sizeResult += filesize("$path/$FolderOrFile");
                }
            }
        }
        closedir($handle);
        return $sizeResult;
    }

	public static function getContent($filename){
    	if(is_file($filename)){
            return file_get_contents($filename);
    	}
    	return false;
	}

    public static function getSize($filename){
    	if(is_file($filename)){
            return filesize($filename);
    	}
    	return 0;
	}

    public static function writeFile($filename, $txt){
        $path = dirname($filename);
		if (!file_exists($path)){
			mkdir($path, 0777, true);
		}
		$file = fopen($filename, 'w') or die("Unable to open file!");
		fwrite($file, $txt);
		fclose($file);
	}

    public static function deleteFile($filename){
        if (is_file($filename)) {
            return \unlink($filename);
        }
        return false;
    }

    protected function encode($data){
		switch ($this->encodedMode) {
            case Storage::SER:
			return serialize($data);
			case Storage::JSN:
			return json_encode($data);
			case Storage::NUM:
			if(is_numeric($data)){
                return strval($data);
            }
			default:
            if(is_string($data)){
                $this->encodedMode = 0;
                return $data;
            }
            return serialize($data);
		}
        return false;
	}

	protected function decode($text){
		switch ($this->encodedMode) {
			case Storage::NUM:
			if(is_numeric($text)){
				return floatval($text);
			}
            case Storage::STR:
			return $text;
			case Storage::JSN:
			return json_decode($text, $this->isArray);
			default:
			return unserialize($text);
		}
        return false;
	}

    public function encodedMode(){
		return $this->encodedMode;
	}

    public function isArray(){
		return $this->isArray;
	}
}