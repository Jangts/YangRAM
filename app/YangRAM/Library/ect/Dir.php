<?php
namespace Library\ect;

use Tangram\ClassLoader;
use Status;
use Tangram\NIDO\DataObject;
/**
 *
 */
class Dir extends DataObject {
	private $path;

	public function __construct($path){
		$this->path = str_replace('//', '/', $path.'/');
		$this->readonly = true;
		$this->build($this->path);
	}

	private function build($path){
		$files = glob($path.'*');
		foreach($files as $file){
			if (!is_dir($file)) {
				$index = str_replace($this->path, '', $file);
				$this->data[$index] = md5_file($file);
	        } else {
	            $this->build($file.'/');
	        }
	    }
	}

	public function compare($path){
		if(is_array($path)){
			$diff = $this->diff($this->data, $path);
			var_dump($diff);
		}
		if(is_string($path)&&is_dir($path)){
			$files = new self($path);
			$diff = $this->diff($this->data, $files->toArray());
			var_dump($diff);
		}
		if(is_a($path, 'Library\ect\Dir')){
			$diff = $this->diff($this->data, $path->toArray());
			var_dump($diff);
		}
		return false;
	}
}
