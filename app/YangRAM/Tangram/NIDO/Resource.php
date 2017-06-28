<?php
namespace Tangram\NIDO;

use RDO;
use AF\Models\Certificates\StdPassport;
use Tangram\CACHE\UserFiles;

/**
 *	Resource
 *	资源对象
 *	新建、读取、删改委托给资源索引器的对象
 */
final class Resource extends DataObject {
    protected static $instance = NULL;

    private
    $key = NULL,
    $files = NULL;

    protected
    $data = [
        'hash'          =>  '',
        'mime'          =>  'text/html',
        'charset'       =>  'utf-8',
        'content'       =>  '',
        'assign'        =>  [],
        'defind'        =>  [],
        'template'      =>  []
    ];
    
    /* 创建单例 */
    public static function instance($request){
		if(self::$instance === NULL){
			self::$instance = new static($request);
		}
		return self::$instance;
	}

    /* 清空所有 */
    public function clear(){
		#
		return false;
	}

    private function __construct($request){
        $this->key = $request->URI_HASH;
    }

    /* 委托 */
    public function delegate(){
		#
		return false;
	}

    /* 更新 */
    public function update(){
		#
		return false;
	}

    /* 删除 */
    public function remove(){
		#
		return false;
	}

    /* 渲染 */
    public function render(){
		#
		return false;
	}

    /* 取出备份 */
    public function copy(){
		#
		return false;
	}
}
