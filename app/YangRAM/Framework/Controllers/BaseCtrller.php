<?php
namespace AF\Controllers;
use Request;
use Application;

/**
 *	Common Application Data Controller
 *	通用应用数据控制器
 *  控制器的基类，提供了控制器的基本属性和方法
 */
abstract class BaseCtrller {
    protected
    $request = NULL,
    $app = NULL,
    $passport = NULL;

    public function __construct(Application $app, Request $request){
        $this->request = $request;
        $this->app = $app;
	}

    protected function checkResourceModification($lastModified) {
		if (isset($_SERVER["HTTP_IF_MODIFIED_SINCE"])){
			if (strtotime($_SERVER["HTTP_IF_MODIFIED_SINCE"]) < $lastModified) {
				return true;
			}
			return false;
		}
		if (isset($_SERVER['HTTP_IF_UNMODIFIED_SINCE'])){
			if (strtotime($_SERVER['HTTP_IF_UNMODIFIED_SINCE']) > $lastModified) {
				return true;
			}
			return false;
		}
		if (isset($_SERVER['HTTP_IF_NONE_MATCH'])){
			if ($_SERVER['HTTP_IF_NONE_MATCH'] !== $etag) {
				return true;
			}
			return false;
		}
		return true;
	}

    protected function checkFileModification($filename) {
        if(is_file($filename)){
            $lastModified = filemtime($filename);
            if($this->checkResourceModification($lastModified)){
                return $filename;
            }
            return false;
        }
        return $filename;
	}
}
