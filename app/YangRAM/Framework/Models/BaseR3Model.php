<?php
namespace AF\Models;

use PDO;
use Status;
use System\NIDO\DataObject;
use Storage;
use RDO;
use System\ORM\RDOAdvanced;

/**
 *	RDB Record Row Data Model
 *	关系数据库行数据模型
 *  关系数据模型的进一步拓展，此模型更加
 *  紧密地与数据库中的某个表关联，一个
 *  实例代表了数据库中的一行数据
 *  本模型是用来拓展其他模型类的基类，属于抽象类，并不能直接使用
 */
abstract class BaseR3Model extends BaseModel {
    private static
    $rdos = [],
    $lifetimes = [];

    protected static
    $conn = 0,
    $conn_type = 1,
    $table_prefix = _DBPRE_,
    $table = '',
    $ca_path = '',
    $indexes = ['id'],
    $aikey = NULL,
    $lifetime = 7200,
    $defaults = [
        'id'    =>  0
    ],
    $constraint = [];

    final protected static function getRDO(){
        $class = strtolower(get_called_class());
        if(empty($rdos[$class])){
            if(self::$conn_type){
                $rdos[$class] = new RDOAdvanced(static::$conn);
            }else{
                $rdos[$class] = new RDO(static::$conn);
            }
            if(empty(static::$table)){
                $classname = explode("\\", $class);
                $table = static::$table_prefix . end($classname);
            }else{
                $table = static::$table;
            }
            $rdos[$class]->using($table);
        }
        return $rdos[$class];
    }

    final public static function setLifeTime($time = 2, $unit = BaseModel::H){
		if(is_numeric($time)&&is_numeric($unit)){
            self::$lifetimes[$class] = $time * $unit;
            return true;
        }
        return false;
	}

    final public static function getLifeTime(){
        $class = strtolower(get_called_class());
        if(empty(self::$lifetimes[$class])){
            self::$lifetimes[$class] = static::$lifetime;
        }
        return self::$lifetimes[$class];
	}

    final public static function post ($data){
        $obj = new static();
        $obj->build($data, false);
        return $obj->save();
    }

    final public static function update ($require , $data){
        if(is_object($data)){
            $data = get_object_vars($data);
        }
        if(is_array($data)&&$rdo->requiring($require)->update($date)){
            self::clean();
            return true;
        }
        return false;
    }

    protected static function querySelect($rdo, $require, $orderby, $range, $selecte = '*'){
		if(is_numeric($require)){
            $range = $require;
            $require = "1";
        }elseif(is_string($require)||is_array($require)){
            $require = $require;
        }else{
            $require = "1";
        }
		if(is_numeric($range)){
			$rdo->requiring($require)->take($range)->orderby(false);
		}elseif(is_array($range)){
			$rdo->requiring($require)->take($range[1], $range[0])->orderby(false);
		}else{
			$rdo->requiring($require)->take(0)->orderby(false);
		}
        foreach ($orderby as $order) {
            static::querySort($order, $rdo);
        }
        return $rdo->select($selecte);
	}

    protected static function querySort($order, $rdo){
        if(isset($order[0])&&isset($order[1])){
            if(isset($order[2])){
                switch($order[2]){
                    case DataObject::SORT_CONVERT_GBK:
                    $orderFieldName = 'CONVERT('.(string)$order[0].' USING gbk)';

                    default:
                    $orderFieldName = (string)$order[0];
                }
            }else{
                $orderFieldName = (string)$order[0];
            }
            $rdo->orderby($orderFieldName, !!$order[1]);
        }
    }

    public static function query ($require = "0" , array $orderby = [['1', false, DataObject::SORT_REGULAR]], $range = 0, $format = BaseModel::LIST_AS_OBJ){
        $rdo = self::getRDO();
        $objs = [];
        $result = self::querySelect($rdo, $require, $orderby, $range);
        if($result){
            if($format===BaseModel::LIST_AS_ARR){
                return $result->toArray();
            }
            $pdos = $result->getPDOStatement();
            while($pdos&&$data = $pdos->fetch(PDO::FETCH_ASSOC)){
                $obj = new static();
                $obj->build($data, true);
                $objs[] = $obj;
            }
        }
        return $objs;
    }

    public static function count($require = "0") {
		$rdo = self::getRDO();
		$rdo->requiring($require)->orderby(false)->take(0);
		return $rdo->count();
	}

    public static function all (){
        $rdo = self::getRDO();
        $objs = [];
        $result = $rdo->requiring()->take(0)->orderby(false)->select();
        if($result){
            $pdos = $result->getPDOStatement();
            while($pdos&&$data = $pdos->fetch(PDO::FETCH_ASSOC)){
                $obj = new static();
                $obj->build($data, true);
                $objs[] = $obj;
            }
        }
        return $objs;
    }

    public static function find ($key, $val, $index = false, $ok = '1'){
        if(is_numeric($index)){
            if($index>=0){
                return self::query([$key=>$val], [[$ok, false, DataObject::SORT_REGULAR]], [1, $index]);
            }
            return self::query([$key=>$val], [[$ok, true, DataObject::SORT_REGULAR]], [1, -1 - $index]);
        }
        if(is_bool($index)){
            return self::query([$key=>$val], [[$ok, $index, DataObject::SORT_REGULAR]], 0);
        }
        return self::query([$key=>$val], [[$ok, false, DataObject::SORT_REGULAR]], 0);
    }

    public static function identity($identity){
        if(count(static::$indexes)>0){
            $pk = static::$indexes[0];
            $rdo = self::getRDO();
            $storage = self::getStorage();
            $lifetime = self::getLifeTime();
            if(is_a($storage, 'System\CACH\Storage')){
                if($data = $storage->take($identity)){
                    if($lifetime){
                        $time = $storage->time($identity);
                        if($time&&($time + $lifetime > time())){
                            $obj = new static();
                            $obj->build($data, true);
                            return $obj;
                        }
                    }else{
                        $obj = new static();
                        $obj->build($data, true);
                        return $obj;
                    }
                }
                $result = $rdo->requiring()->where($pk, $identity)->select();
                if($result&&$data = $result->getRow()){
                    $obj = new static();
                    $obj->build($data, true);
                    $storage->store($identity, $data);
                    return $obj;
                }
            }else{
                $result = $rdo->requiring()->where($pk, $identity)->select();
                if($result&&$data = $result->getRow()){
                    $obj = new static();
                    $obj->build($data, true);
                    return $obj;
                }
            }
        }
        return false;
    }

    public static function __callStatic($name, array $arguments){
        if(count(static::$indexes)>0&&count($arguments)>0){
            $name = strtolower($name);
            if($name===static::$indexes[0]){
                return self::identity($arguments[0]);
            }
            if(in_array($name, static::$indexes)){
                $result = self::getRDO()->requiring()->where($name, $arguments[0])->select();
                if($result&&$data = $result->getRow()){
                    $obj = new static();
                    $obj->build($data, true);
                    return $obj;
                }
            }
        }
        return false;
    }

    final public static function delete ($require){
        $rdo = self::getRDO();
        if(method_exists(get_called_class(), 'destroy')){
            $objs = self::query($require);
		    $rdo->begin();
		    foreach($objs as $obj){
                if($obj->destroy()){
                    continue;
                }
                $rdo->rollBack();
                return false;
		    }
		    $rdo->commit();
            return true;
        }
        if($rdo->requiring($require)->delete()){
            self::clean();
            return true;
        }
        return false;        
    }

    protected
    $rdo,
    $pk,
    $storage,
    $posted;

    final public function __construct(){
        $this->rdo = self::getRDO();
        $this->storage = self::getStorage();
        $this->data = static::$defaults;
        if(isset(static::$indexes[0])){
            $this->pk = static::$indexes[0];
        }else{
            new Status(703.4, 'Using Module Error', 'Must Have An Index Field!', true);
        }
    }

    protected function build($data, $posted = false){
        $this->xml = NULL;
        $this->readonly = false;
        if(is_object($data)){
            $data = get_object_vars($data);
        }
        if(is_array($data)){
            $this->data = array_merge(static::$defaults, array_intersect_key($data, static::$defaults));
        }
        if(isset($this->data[$this->pk])){
            $this->_hash = $this->data[$this->pk];
        }
        if($posted){
            $this->posted = $this->data;
        }
        return $this;
    }

    public function put($data){
        foreach(static::$indexes as $key){
            unset($data[$key]);
        }
        if(is_array($data)){
            $this->data = array_merge($this->data, array_intersect_key($data, static::$defaults));
        }
        return $this;
    }

    public function cln(){
		$obj = new self;
		return $obj->put($this->data);
	}

    public function save (){
        $rdo = $this->rdo;
        if($this->posted){
            if($this->readonly||empty($this->_hash)){
                return false;
            }
            $diff = $this->diff($this->data, $this->posted, DataObject::DIFF_SIMPLE);
            $data = $diff['__M__'];
            if(count($data)==0){
                return $this;
            }
            if($rdo->requiring()->where($this->pk, $this->_hash)->update($data)){
                foreach ($data as $key => $val) {
                    $this->posted[$key] = $val;
                }
            }else{
                return false;
            }
        }else{
            if(static::$aikey) {
                unset($this->data[static::$aikey]);
            }
            if(!$rdo->insert($this->data)){
                return false;
            }
            if(isset($this->data[$this->pk])){
                $this->_hash = $this->data[$this->pk];
            }elseif($this->pk===static::$aikey){
                $this->_hash = $rdo->lastInsertId(static::$aikey);
            }else{
                new Status(703);
            }
            $result = $rdo->requiring()->where($this->pk, $this->_hash)->select();
            $data = $result->getRow();
            $this->posted = $this->data = $data;
        }
        if($this->storage&&$this->posted){
            $this->storage->store($this->_hash);
        }
        return $this;
    }

    public function destroy (){
        if($this->posted&&($this->rdo->requiring()->where($this->pk, $this->_hash)->delete())){
            if($this->storage) $this->storage->store($this->_hash);
            return $this->afterDelete();
        }
        return false;
    }

    protected function afterDelete(){
        return true;
    }
}