<?php
namespace Tangram\ORM\traits;

use PDO;

/**
 *	Transaction Trait
 *	事件处理特性
 */
trait transaction {
    public function begin($commitPrevious = true, $rollBackPrevious = true){
        if($this->pdox->inTransaction()){
            if($commitPrevious){
                $this->pdox->commit();
                return $this->pdox->beginTransaction();
            }
            if($rollBackPrevious){
                $this->pdox->rollBack();
                return $this->pdox->beginTransaction();
            }
            return true;
        }
        return $this->pdox->beginTransaction();
    }

    public function is_intrans(){
        return $this->pdox->inTransaction();
    }

    public function rollBack(){
        if($this->pdox->inTransaction()){
            return $this->pdox->rollBack();
        }
        return false;
    }

    public function commit(){
        if($this->pdox->inTransaction()){
            return $this->pdox->commit();
        }
        return false;
    }
}