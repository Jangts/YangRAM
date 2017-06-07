<?php
namespace AF\Models;

use System\NIDO\DataObject;
use Storage;

/**
 *	Relational Data Model
 *	关系数据模型，简称模型
 *  一般数据封装包的拓展，相比于一般数据封装包数据结构的灵活
 *  模型数据结构固定，一个
 *  模型类的所有实例拥有着相同的结构，因而能
 *  提供一些专门争对某类数据操作的接口
 *  本模型是用来拓展其他模型类的基类，属于抽象类，并不能直接使用
 *  直接继承自一般数据封装包，且固定的数据结构的类，也可视其为模型
 */
abstract class BaseModel extends DataObject {
    const
	S = 1,
    M = 60,
	H = 3600,
	D = 86400,
	W = 604800,

    LIST_AS_OBJ = 0,
    LIST_AS_ARR = 123;
    
    private static
    $storages = [],
    $memories = [];

    protected static
    $ca_path,
    $ca_encode_mode = Storage::JSN,
    $defaults = [];

    protected
    $_hash,
    $readonly = false,
    $template = '',
    $data = [];

    final protected static function getStorage(){
        $class = strtolower(get_called_class());
        if(empty(self::$storages[$class])){
            if(isset(static::$ca_path)&&(strpos(static::$ca_path, ROOT)===0)){
                self::$storages[$class] = new Storage(static::$ca_path, static::$ca_encode_mode, true);
            }else{
                return NULL;
            }
        }
        return self::$storages[$class];
    }

    final protected static function getMemory($index = NULL){
        $class = strtolower(get_called_class());
        if(empty(self::$memories[$class])){
            self::$memories[$class] = [];
        }
        if($index===NULL){
            return self::$memories[$class];
        }
        return isset(self::$memories[$class][$index]) ? self::$memories[$class][$index] : NULL;
    }

    final public static function clean(){
        $storage = self::getStorage();
        if(is_a($storage, '\System\CACH\Storage')){
            $storage->cleanOut();
        }
    }

    public function toXml($root = 'data', $version = '1.0', $encoding = 'UTF-8'){
        if($this->xml){
            return $this->xml->outputMemory(true);
        }
        return self::getXmlbyArray($this->data, $root, $version, $encoding);
    }

    public static function all(){
        return [];
    }

    public static function identity($identity){
        return new self($identity);
    }

    protected function __construct($identity){
        $this->name = $identity;
        $this->build(static::$defaults);
    }

    protected function build($data){
        $this->xml = NULL;
        if(is_array($data)||is_object($data)){
            foreach ($data as $key => $value) {
                if(array_key_exists($key, static::$defaults)){
                    $this->data[$key] = $value;
                }
            }
        }
        return $this;
    }

    public function put($data){
        if(($this->readonly===false)){
            return $this->build($data);
        }
        return $this;
    }

    final public function cac(){
        if(is_a(static::$storage, '\System\CACH\Storage')){
            static::$storage->store($this->name, $this->data);
        }
    }

    final public function add($property, $value){
        return false;
    }

    final public function uns($property){
        return false;
    }

    public function render(){
        if(is_file($this->template)){
            if(is_array($this->data)){
                extract($this->data, EXTR_PREFIX_SAME, 'CSTM');
            }
            include_once $template;
            return true;
        }
        return false;
    }
}