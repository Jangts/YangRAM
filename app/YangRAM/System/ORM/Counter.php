<?php
namespace System\ORM;

use PDO;
use Status;

/**
 *	Universal Counter
 *	通用计数器
 *	用来解决只读状态下无法写入计数的问题
 */
class Counter {
    use traits\common;

    protected
    $pdox = NULL,
    $queryString = '',
    $table = NULL,
    $primary_key_field = NULL,
    $primary_key_value = NULL,
    $prepare = NULL,
    $count_field = "KEY_COUNT";

    final public function __construct($table, $conn = 0){     
        if($table===DB_SYS.'apps'){
            $this->table = $table;
        }else if(self::get_permissions_code($table = self::escape($table))){
			$this->table = $table;
		}else{
            new Status(703.5, 'Invalid Table Name', true);
        }
        $this->pdox = self::conn($conn);
        $this->prepare();
    }

    final public function setKey($p_field = false){
        if(is_string($p_field)){
            $this->primary_key_field = self::escape($p_field);
        }
        $this->prepare();
        return $this;
    }

    final public function setFields($c_field = 'KEY_COUNT', $p_field = false){
        $this->count_field = self::escape($c_field);
        return $this->setKey($p_field);
    }

    final protected function prepare(){
        if($this->pdox&&$this->count_field&&$this->primary_key_field){
            $queryString = "UPDATE `$this->table` SET `$this->count_field` = `$this->count_field` + :count WHERE `$this->primary_key_field` = :key";
            $this->prepare = $this->pdox->prepare($queryString, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
        }
    }

    final public function point($key){
        if(is_numeric($key) || ($key = self::escape((string)$key))){
            $this->primary_key_value = $key;
            return $this;
        }
        return false;
    }

    final public function read(){
        if($this->pdox){
            $queryString .= "SELECT `$this->count_field` FROM `$this->table` WHERE `$this->primary_key_field` = '$this->primary_key_value'";
            $statement = $this->pdox->query($queryString);
            if($statement&&$row=$statement->fetch(PDO::FETCH_ASSOC)){
                return $row[$this->count_field];
            }
        }
        return false;
    }

    final public function add($step = 1){
        if($this->prepare){
            $step = intval($step);
            $this->prepare->execute([':count' => $step, ':key' => $this->primary_key_value]);
            return $this;
        }
        return false;
    }

    final public function reset($value = 0, $anotherfield = NULL){
        if($this->pdox){
            if($field = self::escape((string)$anotherfield)){
                $value = intval(!!$value);
            }else{
                $field = $this->count_field;
                $value = intval($value);
            }
            $queryString = "UPDATE `$this->table` SET `$field` = $value WHERE `$this->primary_key_field` = '$this->primary_key_value'";
            $this->pdox->query($queryString);
            return $this;
        }
        return false;
    }
}
