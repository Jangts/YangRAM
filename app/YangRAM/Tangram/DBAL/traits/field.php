<?php
namespace Tangram\DBAL\traits;

use PDO;

/**
 *	Fields Manage Trait
 *	字段管理特性
 */
trait field {
    public function getFields(){
        if(count($this->tables)===1){
            return $this->pdox->getTableFields($this->tables[0]);
        }
        $fields = [];
        foreach($this->tables as $tablename){
            $fields[$tablename] = $this->pdox->getTableFields($tablename);
        }
        return $fields;
    }

    public function addField($fieldname, $fieldtype = 'INT NOT NULL', $otherset = ''){
        $fieldset = $this->pdox->trueSetString([$fieldset, $otherset]);
        if(count($this->tables)===1){
            $sql = sprintf('ALTER TABLE `%s` ADD `%s` %s;', $this->tables[0], $fieldname, $fieldset);
            return $this->pdox->exec($sql);
        }
        $results = [];
        $sql = sprintf('ALTER TABLE `?` ADD `%s` %s;', $fieldname, $fieldset);
        $prepare = $this->pdox->prepare($sql);
        foreach($this->tables as $tablename){
            $results[$tablename] = $prepare->execute([$tablename]);
        }
        return $results;
    }

    public function changeField($fieldname, $fieldset = '', $newname = NULL){
        $newname = ($newname?$newname:$fieldname);
        $fieldset = $this->pdox->trueSetString([$fieldset]);
        if(count($this->tables)===1){
            $sql = sprintf('ALTER TABLE `%s` CHANGE `%s` `%s` %s;', $this->tables[0], $fieldname, $newname, $fieldset);
            return $this->pdox->exec($sql);
        }
        $results = [];
        $sql = sprintf('ALTER TABLE `?` CHANGE `%s` `%s` %s;', $fieldname, $newname, $fieldset);
        $prepare = $this->pdox->prepare($sql);
        foreach($this->tables as $tablename){
            $results[$tablename] = $prepare->execute([$tablename]);
        }
        return $results;
    }

    public function renameField($fieldname, $newname){
        $newname = ($newname?$newname:$fieldname);
        if(count($this->tables)===1){
            $sql = sprintf('ALTER TABLE `%s` CHANGE `%s` `%s`;', $this->tables[0], $fieldname, $newname);
            return $this->pdox->exec($sql);
        }
        $results = [];
        $sql = sprintf('ALTER TABLE `?` CHANGE `%s` `%s`;', $fieldname, $newname);
        $prepare = $this->pdox->prepare($sql);
        foreach($this->tables as $tablename){
            $results[$tablename] = $prepare->execute([$tablename]);
        }
        return $results;
    }

    public function modifyField($fieldname, $fieldset = ''){
        $fieldset = $this->pdox->trueSetString([$fieldset]);
        if(count($this->tables)===1){
            $sql = sprintf('ALTER TABLE `%s` MODIFY `%s` %s;', $this->tables[0], $fieldname, $fieldset);
            return $this->pdox->exec($sql);
        }
        $results = [];
        $sql = sprintf('ALTER TABLE `?` MODIFY `%s` %s;', $fieldname, $fieldset);
        $prepare = $this->pdox->prepare($sql);
        foreach($this->tables as $tablename){
            $results[$tablename] = $prepare->execute([$tablename]);
        }
        return $results;
    }

    public function dropField($fieldname){
        $fieldset = $this->pdox->trueSetString($fieldset);
        if(count($this->tables)===1){
            $sql = sprintf('ALTER TABLE `%s` DROP `%s`;', $this->tables[0], $fieldname);
            return $this->pdox->exec($sql);
        }
        $results = [];
        $sql = sprintf('ALTER TABLE `?` DROP `%s`;', $fieldname);
        $prepare = $this->pdox->prepare($sql);
        foreach($this->tables as $tablename){
            $results[$tablename] = $prepare->execute([$tablename]);
        }
        return $results;
    }

    public function setDefault($fieldName, $value){
        $results = [];
        foreach($this->tables as $tablename){
            $results[$tablename] = $this->pdox->addDefaultValue($tablename, $fieldName, $value);
        }
        if(count($results)===1){
             return $results[0];
        }
        return $results;
    }

    public function unsetDefault($fieldName){
        $results = [];
        foreach($this->tables as $tablename){
            $results[$tablename] = $this->pdox->dropDefaultValue($tablename, $fieldName);
        }
        if(count($results)===1){
             return $results[0];
        }
        return $results;
    }

    public function setPrimaryKey($primaryKey){
        $num = func_num_args();
        $results = [];
        if($num>1){
            $args = func_get_args();
            foreach($this->tables as $tablename){
                $results[$tablename] = $this->pdox->addUnionPrimaryKey($tablename, $args);
            }
        }else{
            foreach($this->tables as $tablename){
                $results[$tablename] = $this->pdox->addPrimaryKey($tablename, $primaryKey);
            }
        }
        if(count($results)===1){
             return $results[0];
        }
        return $results;
    }

    public function unsetPrimaryKey($primaryKeyName = NULL){
        $results = [];
        foreach($this->tables as $tablename){
            $results[$tablename] = $this->pdox->dropPrimaryKey($tablename, $primaryKeyName);
        }
        if(count($results)===1){
             return $results[0];
        }
        return $results;
    }

    public function setIncrement($int = 1){
        $results = [];
        foreach($this->tables as $tablename){
            $results[$tablename] = $this->pdox->setIncrement($tablename, $int);
        }
        if(count($results)===1){
             return $results[0];
        }
        return $results;
    }

    public function setForeignKey($field, $foreignTable, $foreignField, $foreignKeyName = NULL){
        $results = [];
        foreach($this->tables as $tablename){
            $results[$tablename] = $this->pdox->dropForeignKey($tablename, $field, $foreignTable, $foreignField, $foreignKeyName);
        }
        if(count($results)===1){
             return $results[0];
        }
        return $results;
    }

    public function unsetForeignKey($foreignKeyName){
        $results = [];
        foreach($this->tables as $tablename){
            $results[$tablename] = $this->pdox->dropForeignKey($tablename, $foreignKeyName);
        }
        if(count($results)===1){
             return $results[0];
        }
        return $results;
    }

    public function addIndex($fieldName, $unique = false){
        $results = [];
        foreach($this->tables as $tablename){
            $results[$tablename] = $this->pdox->createIndexConstraint($tablename, $fieldName, $fieldName, $unique);
        }
        if(count($results)===1){
             return $results[0];
        }
        return $results;
    }

    public function addUnionIndex($indexName, array $fields, $unique = false){
        $results = [];
        foreach($this->tables as $tablename){
            $results[$tablename] = $this->pdox->createIndexConstraint($tablename, $indexName, $fields, $unique);
        }
        if(count($results)===1){
             return $results[0];
        }
        return $results;
    }

    public function dropIndex($indexname){
        $results = [];
        foreach($this->tables as $tablename){
            $results[$tablename] = $this->pdox->dropIndexConstraint($tablename, $indexname);
        }
        if(count($results)===1){
             return $results[0];
        }
        return $results;
    }

    public function addCheck($condition, $checkName = NULL){
        $results = [];
        foreach($this->tables as $tablename){
            $results[$tablename] = $this->pdox->addCheckConstraint($tablename, $condition, $checkName);
        }
        if(count($results)===1){
             return $results[0];
        }
        return $results;
    }

    public function dropCheck($checkName){
        $results = [];
        foreach($this->tables as $tablename){
            $results[$tablename] = $this->pdox->dropCheckConstraint($tablename, $checkName);
        }
        if(count($results)===1){
             return $results[0];
        }
        return $results;
    }
}