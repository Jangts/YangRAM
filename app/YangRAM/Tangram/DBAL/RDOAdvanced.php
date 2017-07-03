<?php
namespace Tangram\DBAL;

use PDO;

/**
 *	NI RDBTable Level Data Object
 *	NI关系数据库表级数据对象，是Tangram\DBAL\RDO的扩展版，支持
 *  **  用来对数据库中的行级单元进行增，删，改，查，另外新增了
 *  **  事件功能（需要数据库自身支持）
 *  **  ***  表级处理功能，如
 *	**  ***  表的创建
 *	**  ***  表属性的创建
 *	**  ***  表字段的创建
 *	**  ***  表的删除
  *	**  ***  复制表
 *	**  ***  查询所有表
  *	**  ***  查询表信息
 */
final class RDOAdvanced extends RDO {
    use traits\transaction;
    use traits\field;

    public function getTables($current = false){
        return $this->pdox->showTables($current);
    }

    public function setAttr($attr, $val){
        return $this->pdox->setAttribute($attr, $val);
    }

    public function getAttr($attr){
        return $this->pdox->getAttribute($attr);
    }

    public function like($table, $selected = NULL, $engine = 'InnoDB'){
        $results = [];
        if(is_array($table)){
            foreach($this->tables as $tablename){
                $results[$tablename] = $this->pdox->createTable($tablename, $table, $engine);
            }
        }elseif(is_string($table)){
            foreach($this->tables as $tablename){
                $results[$tablename] = $this->pdox->cloneTable($tablename, $table, $selected);
            }
        }elseif(is_null($table)){
            foreach($this->tables as $tablename){
                $results[$tablename] = $this->pdox->dropTable($tablename);
            }
        }
        if(count($results)===1){
            return current($results);
        }
        return $results;
    }

    public function rename(){
        $newnames = func_get_args();
        $results = [];
        foreach($newnames as $n => $newname){
            if(isset($this->tables[$n])){
                $results[$this->tables[$n]] = $this->pdox->renameTable($this->tables[$n], $newname);
            }
        }
        if(count($results)===1){
            return current($results);
        }
        return $results;
    }

    public function truncate(){
        $results = [];
        foreach($this->tables as $tablename){
            $results[$tablename] = $this->pdox->truncateTable($tablename);
        }
        if(count($results)===1){
            return current($results);
        }
        return $results;
    }

    public function setEngine($engine){
        $results = [];
        foreach($this->tables as $tablename){
            $results[$tablename] = $this->pdox->changeTableEngine($tablename, $engine);
        }
        if(count($results)===1){
            return current($results);
        }
        return $results;
    }

    public function drop(){
        if(count($this->tables)===1){
            $sql = sprintf('DROP TABLE `%s`', $this->tables[0]);
            return $this->pdox->exec($sql);
        }
        $results = [];
        $prepare = $this->pdox->prepare('DROP TABLE `?`');
        foreach($this->tables as $tablename){
            $results[$tablename] = $prepare->execute([$tablename]);
        }
        return $results;
    }
}
