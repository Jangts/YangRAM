<?php
namespace System;

use System\STAT\Status;

/**
 *	File ClassLoader Module
 *	文件加载模块
 *	提供安全的批量加载（主动）方法和懒加载（被动）的配置接口
 */
final class ClassLoader {
	private static $instance = NULL;

	private
	$map = [];

	public static function instance(){
		if(self::$instance === NULL){
			spl_autoload_register([new self, 'includeClassOfNSMap']);
			self::setNSMap([
				'System'	=>	PATH_SYS,
				'Library'	=>	PATH_LIB,
				'Packages'	=>	PATH_LIBX,
				'AF'		=>	PATH_NIAF,
				'CM'		=>	PATH_NIAF.'Models/CMF/'			
			]);
			return self::$instance;
		}
	}

	public static function setNSMap(array $map){
		foreach ($map as  $ns => $path) {
			self::$instance->map[] = [str_replace('\\', '\/', $ns), $path];
		}
		return true;
	}

	private function __construct(){
		self::$instance = $this;
	}

	private function includeOne($filename){
		$filename = str_replace('\\', '/', $filename);
		$realpath = realpath($filename);
		if($realpath){
			include_once($realpath);
		}else{
			new Status('Could Not Find Required File : '.Response::trimServerFilename($filename), true);
		}
	}

	private function includeMultiple($names, $mainPath = '', $extensions = '.php'){
		if(is_array($names)){
			foreach ($names as $name) {
				$this->includeOne((string)$mainPath.$name.$extensions);
			}
		}elseif(is_string($names)){
			$this->includeAPath($mainPath->$mainPath, $extensions);
		}
	}

	private function includeAPath($path, $extensions){
		$dh = opendir($path);
		$pa = "/\.".$extensions."$/";
		while ($filename = readdir($dh)) {
			if ($filename != "." && $filename != "..") {
				$fullpath = $path."/".$filename;
				if (!is_dir($fullpath)) {
					$this->includeAPath($fullpath, $extensions);
				} else {
					if(preg_match($pa, $fullpath)){
						$this->includeOne($fullpath);
					}
				}
			}
		}
		closedir($dh);
	}

	public function includeClassOfNSMap($classname){
		$_classname = str_replace('\\', '/', $classname);
		foreach ($this->map as $set) {
			$ns = $set[0];
			$path = $set[1];
			if(@preg_match('/^'.$ns.'\//', $_classname)){
				$filename = preg_replace('/^'.$ns.'\//', $path, $_classname).'.php';
				if(is_file($filename)){
					$this->includeOne($filename);
					if((class_exists($classname)||trait_exists($classname)||interface_exists($classname)||function_exists($classname))==false){
						$status = new Status(705, '', 'Class '.$classname.' not found in file '.$filename.', this file must be tampered.');
						$tracedata = $status->getTrace();
						$status->write(isset($tracedata[1]['file']) ? $tracedata[1]['file'] : $tracedata[1]['function']);
						return $status->cast(Status::CAST_LOG);
					}
				}else{
					$status = new Status(708.3, 'File Not Found', 'File of class '.$classname.' not found, files on your yangram may be tampered, or just a spelling mistake. ( ' .$filename. ' )');
					$tracedata = $status->getTrace();
					$status->write(isset($tracedata[1]['file']) ? $tracedata[1]['file'] : $tracedata[1]['function']);
					return $status->cast(Status::CAST_LOG);
				}
				return;
			}
		}
	}

	public static function execute($names, $mainPath = '', $extensions = '.php'){
		if(is_string($names)&&($names!=='*')){
			self::$instance->includeOne($mainPath.$names.$extensions);
		}else{
			self::$instance->includeMultiple($names, $mainPath, $extensions);
		}
		return true;
	}
}
