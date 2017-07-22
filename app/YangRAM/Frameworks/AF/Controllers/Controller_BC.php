<?php
namespace AF\Controllers;
use Request;
use Application;

/**
 *	Common Application Data Controller
 *	通用应用数据控制器
 *  控制器的基类，提供了控制器的基本属性和方法
 */
abstract class Controller_BC {
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

	protected function cacheResource($filename, $expires = 3153600000,  $cactrl = 'public') {
		if(is_file($filename)){
            $lastModified = filemtime($filename);
            if(!$this->checkResourceModification($lastModified)){
                header("HTTP/1.1 304 Not Modified");
				exit;
            }
        }
        header('Cache-Control: '.$cactrl);
        header('Cache-Control: max-age='.$expires);
        header('Expires: ' . preg_replace('/.{5}$/', 'GMT', gmdate('r', intval(time() + $expires))));
        header('Last-Modified: ' . gmdate("D, d M Y H:i:s", time()).' GMT');
        return $this;
	}
}
