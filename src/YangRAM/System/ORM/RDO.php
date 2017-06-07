<?php
namespace System\ORM;

use PDO;
use Status;

/**
 *	NI RDBRow Level Data Object
 *	NI关系数据库行级数据对象，主要
 *	用来对数据库中的行级单元进行增，删，改，查，其中
 *	**  静态方法只用来快速查询
 *  **  动态方法才能实现完整的增，删，改，查
 *  **  ***  动态方法插入功能使用PDO模板技术，安全低耗
 */
class RDO {
    use traits\common;

    protected static
	$conn = NULL,
	$storage = NULL;

    private static function query($queryString, $pdox){
        if($result = $pdox->query($queryString)){
            return new RDOSelectResult($result);
        }
        return false;
	}

    public static function setPDOX($id = 0){
		self::$conn = self::conn($id);
	}

    public static function getPDOX(){
		if(self::$permissions->APP_PDOXS_GETABLE){
            return self::$conn;
		}
		return NULL;
    }

    public static function get($table, $require = "1", $order = "1 ASC", $select = "*"){
		return self::query(self::getQueryString($table, $require, $order, 0, 0, $select), self::$conn);
	}

    public static function arr($table, $require = "1", $order = "1 ASC", $select = "*"){
		if($result = self::get($table, $require, $order, $select)){
			return $return->toArray();
		}
		return [];
	}

    public static function xml($table, $require = "1", $order = "1 ASC", $select = "*"){
		if($result = self::get($table, $require, $order, $select)){
			return $return->toXml();
		}
		return '<?xml version="1.0" encoding="utf-8"?><result></result>';
	}

    public static function json($table, $require = "1", $order = "1 ASC", $select = "*"){
		if($result = self::get($table, $require, $order, $select)){
			return $return->toJson();
		}
		return '[]';
	}

    public static function tops($table, $require = "1", $num = "10", $order = "1 ASC", $select = "*"){
		return self::query(self::getQueryString($table, $require, $order, 0, $num, $select), self::$conn);
	}

    public static function one($table, $require = "1", $order = "1 ASC", $select = "*"){
		$rows = self::query(self::getQueryString($table, $require, $order, 0, "1", $select), self::$conn);
        if($rows&&$row = $rows->getRow()){
            return $row;
        }
        return false;
	}

	public static function id($table, $id, $select = "*"){
		$id = sprintf('%s', $id);
		return self::one($table, "id = '$id'", "1 ASC", $select);
	}

    public static function multtable(array $left, array $right){
        $tableLeft = $left['table'];
        $fieldLeft = $left['field'];
        $aliasLeft = isset($left['as']) ? $left['as'] : 'L';
        $tableRight = $right['table'];
        $fieldRight = $right['field'];
        $aliasRight = isset($right['as']) ? $right['as'] : 'R';
		$table = "%s` AS %s RIGHT JOIN `%s` AS %s ON %s.`%s` = %s.`%s";
		return sprintf($table, $tableRight, $aliasRight, $tableLeft, $aliasLeft, $aliasRight, $fieldRight, $aliasLeft, $fieldLeft);
	}

    public static function join(array $left, array $right, $require = "1", $order = "1 ASC", $select = "*", $num = 0){
		$table = self::multtable($left, $right);
		return self::query(self::getQueryString("`$table`", $require, $order, 0, $num, $select), self::$conn);
	}

    public static function num($table, $require = "1"){
        if($result = self::$conn(self::getQueryString($table, $require, "1 ASC", NULL, 'count(*) as sum'))){
            return intval($result->fetchColumn());
        }
        return 0;
	}

    protected
    $pdox = NULL,
	$tables = [],
    $requires = [],
    $order_fields = [],
    $orders = [],
    $select = NULL,
    $start = 0,
	$length = 0,
    $insertPrepare = [
        'queryString'   =>  NULL,
        'PDOStatement'  =>  NULL
    ];

    public
    $lastQueryString = '';

    final public function __construct($options = 0){
        if($rdox = self::conn($options)){
            $this->pdox = $rdox;
        }else{
            $status =  new Status(703.2, 'Database Connect Error', 'Please check your arguments for System\RDO');
            return $status->cast(Status::CAST_LOG);
        }
    }

    final public function using($tablename1){
        if(is_array($tablename1)){
            $this->tables = $tablename1();
        }else{
            $this->tables = func_get_args();
        }
        $this->requires = [];
        $this->order_fields = [];
        $this->orders = [];
        return $this;
    }

    final public function where($left, $right = false, $symbol = '='){
        if(is_string($left)&&preg_match('/\w+/', $left)){
            $left = '`' . $left . '`';
        }
        else{
            return $this;
        }
        if($right!==false){
            if(is_string($right)){
                if(is_numeric($right)){
                    if(!in_array($symbol, ['=', 'LIKE', '>', '<', '<>'])){
                        $symbol = '=';
                    }
                }else{
                    $symbol = strtoupper($symbol);
                    if(!in_array($symbol, ['=', '<>', 'IN', 'LIKE'])){
                        $symbol = '=';
                    }
                    if($symbol!=='IN'){
                        $right = "'" . $this->escape($right) . "'";
                    }
                }
                $this->requires[] = $left . ' ' . $symbol . ' ' . $right;
            }
            elseif(is_numeric($right)){
                if(!in_array($symbol, ['=', '>', '<', '<>'])){
                    $symbol = '=';
                }
                $this->requires[] = $left . $symbol . $right;
            }
            elseif(is_null($right)){
                $this->requires[] = $left . ' = NULL';
            }
            elseif(is_array($right)){
                $this->requires[] = $left . " IN ('" . join("','", $right) . "')";
            }
        }
        return $this;
    }

    final public function requiring($require = NULL){
        $this->requires = [];
        if(is_string($require)){
            $this->requires[] = $require;
        }
        if(is_array($require)){
            foreach ($require as $key => $value) {
                $this->requires[] = "`$key`" . "=" . "'$value'";
            }
        }
        return $this;
    }

    final private function condition(){
        $str = 1;
        $count = 0;
        foreach($this->requires as $require){
            if($count==0){
                $str = $require;
            }else{
                $str .= " AND ".$require;
            }
            $count++;
        }
        return $str;
	}

    final public function update(array $data){
        $this->tables==NULL && die("No Database Table");
        if(self::writeable($this->tables[0])&&is_array($data)&&!empty($data)){
            $sql = "UPDATE `%s` SET %s WHERE %s";
            $data = $this->updateString($data);
            $sql = sprintf($sql, self::escape($this->tables[0]), $data, self::escape($this->condition()));
            // if($this->tables[0]==='ni_cnt_of_photos'){
            //     var_dump($sql);
            //     // die;
            // }
            $this->lastQueryString = $sql;
            $num = $this->pdox->exec($sql);
            if(is_numeric($num)){
                return $num;
            };
		}
		return false;
	}

	final private function updateString(array $data){
		$arr = [];
		foreach($data as $key=>$val){
            if(is_string($val)){
                $arr[] = "`".self::escape($key)."`"." = '".self::escape($val)."'";
            }elseif(is_numeric($val)){
                $arr[] = "`".self::escape($key)."`"." = ".(string)$val;
            }elseif(is_bool($val)){
                $arr[] = "`".self::escape($key)."`"." = ".($val ? '1' : '0');
            }else{
                $arr[] = "`".self::escape($key)."`"." = null";
            }

		}
		return join(", ", $arr);
	}

    final public function delete(){
		$this->tables==NULL && die("No Database Table");
        $sql = "DELETE FROM `%s` WHERE %s";
		$sql = sprintf($sql, $this->tables[0], $this->condition());
        $this->lastQueryString = $sql;
        $num = $this->pdox->exec($sql);
        if(is_numeric($num)){
            return $num;
        }
        return false;
	}

    final public function orderby($field, $reverse = false){
        if(is_string($field)||is_numeric($field)){
            if($field&&(!in_array($field, $this->order_fields))){
                $this->order_fields[] = $field;
                if($reverse){
                    $this->orders[] = $field . ' DESC';
                }else{
                    $this->orders[] = $field . ' ASC';
                }
            }
        }else{
            $this->order_fields = [];
            $this->orders = [];
        }
        // var_dump($this->orders);
        return $this;
    }

    final public function take($length, $start = 0){
        $this->start = intval($start);
        $this->length = intval($length);
        return $this;
    }

    final public function select($select = '*', $range = false, $escape = true){
        if(is_numeric($select)||($select === '*')){
            $this->select = $select;
        }else{
            if(is_string($select)){
                if($escape){
                    $array = preg_split('/,\s*/', $select);
                    $this->select = preg_replace('/\s+AS\s+/i', '` AS `', '`' . join('`, `', $array) . '`');
                }else{
                    $this->select = $select;
                }
            }elseif(is_array($select)){
                $this->select = preg_replace('/\s+AS\s+/i', '` AS `', '`' . join('`, `', $select) . '`');
            }else{
                $this->select = '*';
            }
        }
        if($range){
            if(is_numeric($range)){
                $this->start = 0;
                $this->length = $range;
            }elseif(is_array($range)){
                $this->start = intval($range[0]);
                $this->length = intval($range[1]);
            }
        }
        $sql = $this->getQS();
        $this->lastQueryString = $sql;
        return self::query($sql, $this->pdox);
	}
    
    final public function count($anykey = '*'){
        $this->select = 'COUNT('.$anykey.')';

        $this->start = 0;
        $this->length = 0;
        $sql = $this->getQS();
        $this->lastQueryString = $sql;
        if($result = $this->pdox->query($sql)){
            return intval($result->fetchColumn());
        }
        return 0;
	}

    final public function distinct($select){
        $this->select = 'DISTINCT `'.$select.'`';
        $sql = $this->getQS();
        return self::query($sql, $this->pdox);
    }

    final public function first(){
        $this->length = 1;
        $sql = $this->getQS();
        return self::query($sql, $this->pdox);
    }

    final private function getQS(){
		$this->tables==NULL && die("No Database Table");
		$require = $this->condition();
        if(count($this->orders)>0){
            $order = join(',', $this->orders);
        }else{
            $order = '1 ASC';
        }
        $this->start = $this->start< 0 ? 0 : $this->start;
        $this->length = $this->length< 0 ? 0 : $this->length;
		return self::getQueryString($this->tables, $require, $order, $this->length, $this->start, $this->select);
	}

    final public function insert(array $insert, $ignore = false){
        $this->tables==NULL && die("No Database Table");
        if(self::writeable($this->tables[0])){
            $keys = "(`".join("`, `", array_keys($insert))."`)";;
            $vals = "(:".join(", :", array_keys($insert)).")";
            if($ignore){
    			$sql = "INSERT IGNORE";
    		}else{
    			$sql = "INSERT";
    		}
            $sql .= " INTO `".self::escape($this->tables[0])."` $keys VALUES $vals;";
            if($this->insertPrepare['queryString']!=$sql){
                $this->insertPrepare['queryString'] = $sql;
                $this->lastQueryString = $sql;
                $this->insertPrepare['PDOStatement'] = $this->pdox->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            }
            if($this->insertPrepare['PDOStatement']){
                $array = [];
                foreach($insert as $key=>$val){
                    $array[':'.$key] = stripslashes($val);
                }
                // if($this->tables[0]==='ni_cnt_of_photos'){
                //     var_dump($array[':CONTENT']);
                //     //die;
                // }
                $this->insertPrepare['PDOStatement']->execute($array);
                return true;
            }
        }
		return false;
    }

    final public function inserts(array $inserts, $ignore = false){
        $this->tables==NULL && die("No Database Table");
        if(self::writeable($this->tables[0])){
            $keys = "(`".join("`, `", array_keys($inserts[0]))."`)";;
            $vals = "(:".join(", :", array_keys($inserts[0])).")";
            if($ignore){
    			$sql = "INSERT IGNORE";
    		}else{
    			$sql = "INSERT";
    		}
            $sql .= " INTO `".self::escape($this->tables[0])."` $keys VALUES $vals;";
            if($this->insertPrepare['queryString']!=$sql){
                $this->insertPrepare['queryString'] = $sql;
                $this->lastQueryString = $sql;
                $this->insertPrepare['PDOStatement'] = $this->pdox->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            }
            if($this->insertPrepare['PDOStatement']){
                foreach($inserts as $insert){
                    $array = [];
                    foreach($insert as $key=>$val){
                        $array[':'.$key] = $val;
                    }
                    $this->insertPrepare['PDOStatement']->execute($array);
        		}
                return true;
            }
        }
		return false;
    }

    final public function lastInsertId($name = NULL){
        if($this->insertPrepare['PDOStatement']!=NULL){
            return $this->pdox->lastInsertId($name);
        }
        return 0;
    }
}