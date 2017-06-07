<?php
namespace System\ORM;

use PDO;
use PDOException;
use Status;

/**
 *	PHP Data Object Extended
 *	PDO拓展
 *	基于各数据库SQL语法与PDO接口存在差异的显示，和NI希求兼容多种数据库的需求，而拓展的一个PDO替代品
 */
abstract class NI_PDOExtended_BaseClass extends PDO {
	protected static $instances = [];

	public static function instance(array $options){
        if($dsn = static::parseDsn($options)){
            $id = \hash('md4', $dsn);
            if(empty(self::$instances[$id])){
                if(isset($options['driverOptions'])){
                    $driverOptions = $options['driverOptions'];
                }else{
                    $driverOptions = [];
                }
                if(isset($options['dbname'])){
                    $dbname = $options['dbname'];
                }else{
                    $dbname = NULL;
                }
                $obj = new static;
                self::$instances[$id] = $obj->connectAndReturnInstance($dsn, $options['username'], $options['password'], $driverOptions, $dbname);
		    }
		    return self::$instances[$id];
        }
        return NULL;
	}

    public static function parseDsn(array $options){
        $status = new Status(702.3, 'SQL Driver Error', 'SQL Driver File Must Be Tampered, Please Check Your Driver Files');
        $status->log();
    }

    protected $user, $dbname;

    final public function __construct() {}

    final protected function connectAndReturnInstance($dsn, $username, $password, $driverOptions, $dbname) {
		try {
            $this->user = $username;
            $this->dbname = $dbname;
			parent::__construct ($dsn, $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            parent::exec('SET NAMES UTF8');
            return $this;
		} catch (PDOException $e) {
            $status = new Status(703.2, 'Database Connect Error', $e->getMessage());
            if(_USE_DEBUG_MODE_){
                return $status->cast(Status::CAST_TLOG);
            }
            $status->log();
            return NULL;
		}
	}

    public function query($statement){
        try {
            return parent::query($statement);
		} catch (PDOException $e) {
            $status = new Status(703.5, 'SQL Query Error', $statement);
            $status->write($e->getMessage());
            if(_USE_DEBUG_MODE_){
                return $status->cast();
            }
            $status->log();
            return false;
		}
    }

    public function exec($statement){
        try {
            return parent::exec($statement);
		} catch (PDOException $e) {
            $status = new Status(703.5, 'SQL Exec Error', $statement);
            $status->write($e->getMessage());
            if(_USE_DEBUG_MODE_){
                return $status->cast();
            }
            $status->log();
            return false;
		}
    }

    public function trueSqlSting($statement) {
        return $statement;
    }

    public function showTables(){
        return [];
    }

    public function createTable($tablename, array $list){
        $fields = [];
        foreach($list as $option){
            if(is_string($option)){
                $option = explode(' ', $option);
            }
            $fieldname = array_shift($option);
            $fields[] = '`' .$fieldname. '` ' . $this->trueSetString($option);
        }
        $sql = sprintf('CREATE TABLE `%s` (%s);', $tablename, join(',', $fields));
        return $this->exec($sql);
    }

    final public function trueSetString(array $fieldset) {
        $num = count($fieldset);
        switch($num){
            case 0:
            return 'INT NOT NULL';

            case 1:
            return $this->checkFieldAttrs($this->checkFieldType($fieldset[0]));

            case 2:
            return $this->checkFieldType($fieldset[0]) . ' ' . $this->checkFieldType($fieldset[1]);

            default:
            $return = $this->checkFieldType($fieldset[0]);
            $range = range(1, $num - 1);
            foreach($range as $i){
                $return .= ' ' . $this->checkFieldType($fieldset[$i]);
            }
            return $return;
        }
    }

    public function checkFieldType($setString) {
        return $setString;
    }

    public function checkFieldAttrs($setString) {
        return $setString;
    }

    public function cloneTable($tablename, $memowallobecloned, $fieldsbeselected = NULL){
        if(is_array($fieldsbeselected)){
            $fields = [];
            foreach($fields as $fieldname){
                if(preg_match('/^\w+$/', $fieldname)){
                    $fields[] = $fieldname;
                }
            }
            $sql = sprintf('CREATE TABLE `%s` AS SELECT `%s` FROM `%s`;', $tablename, join('`,`', $fields), $memowallobecloned);
            return $this->exec($sql);
        }else{
            $sql = sprintf('CREATE TABLE `%s` LIKE `%s`;', $tablename, $memowallobecloned);
            return $this->exec($sql);
        }
    }

    public function dropTable($tablename){
        $sql = sprintf('DROP TABLE `%s`;', $tablename);
        return $this->exec($sql);
    }

    public function renameTable($oldname, $newname){
        $sql = sprintf('RENAME TABLE `%s` TO `%s`;', $oldname, $newname);
        return $this->exec($sql);
    }

    public function truncateTable($tablename){
		$sql = sprintf('TRUNCATE TABLE `%s`;', $tablename);
        return $this->exec($sql);
    }

    public function changeTableEngine($tablename, $engine){
        return false;
    }

    public function getTableFields($fieldset) {
        return [];
    }

    public function addDefaultValue($tablename, $fieldName, $value){
        $sql = sprintf("ALTER TABLE `%s` ALTER COLUMN `%s` SET DEFAULT '%s';", $tablename, $fieldName, $value);
        return $this->exec($sql);
    }

    public function dropDefaultValue($tablename, $fieldName){
        $sql = sprintf('ALTER TABLE `%s` ALTER COLUMN `%s` DROP DEFAULT;', $tablename, $primaryKeyName);
        return $this->exec($sql);
    }

    public function addPrimaryKey($tablename, $field){
        $sql = sprintf('ALTER TABLE `%s` MODIFY `%s` NOT NULL;', $tablename, $field);
        $sql .= sprintf('ALTER TABLE `%s` ADD PRIMARY KEY (`%s`);', $tablename, $field);
        return $this->exec($sql);
    }

    public function addUnionPrimaryKey($tablename, array $fields){
        $primaryKeyName = 'PK_'.$tablename;
        foreach ($fields as $field) {
            $sql .= sprintf('ALTER TABLE `%s` MODIFY `%s` NOT NULL;', $tablename, $field);
        }
        $sql = sprintf('ALTER TABLE `%s` ADD CONSTRAINT %s PRIMARY KEY (`%s`);', $tablename, $primaryKeyName, join('`,`', $fields));
        return $this->exec($sql);
    }

    public function dropPrimaryKey($tablename, $primaryKeyName = NULL){
        $primaryKeyName = ($primaryKeyName ? $primaryKeyName : 'PK_'.$tablename);
        $sql = sprintf('ALTER TABLE `%s` DROP CONSTRAINT %s;', $tablename, $primaryKeyName);
        return $this->exec($sql);
    }

    public function setIncrement($tablename, $int){
        return false;
    }

    public function addForeignKey($tablename, $field, $foreignTable, $foreignField, $foreignKeyName){
        if($foreignKeyName&&is_string($foreignKeyName)){
            $sql = sprintf(
                'ALTER TABLE `%s` ADD CONSTRAINT `%s` FOREIGN KEY (`%s`) REFERENCES `%s`(`%s`)',
                $tablename, $foreignKeyName, $field, $foreignTable, $foreignField);
        }else{
            $sql = sprintf(
                'ALTER TABLE `%s` ADD FOREIGN KEY (`%s`) REFERENCES `%s`(`%s`)',
                $tablename, $field, $foreignTable, $foreignField);
        }
        return $this->exec($sql);
    }

    public function dropForeignKey($tablename, $foreignKeyName){
        $sql = sprintf('ALTER TABLE `%s` DROP CONSTRAINT %s;', $tablename, $foreignKeyName);
        return $this->exec($sql);
    }

    public function createIndexConstraint($tablename, $indexname, $fields, $unique = false){
        if($unique){
            $sql = sprintf('CREATE UNIQUE INDEX %s ON `%s`', $indexname, $tablename);
        }else{
            $sql = sprintf('CREATE INDEX %s ON `%s`', $indexname, $tablename);
        }
        if(is_array($fields)){
            $sql .= '(`' . join('`,`', $fields) . '`)';
            return $this->exec($sql);
        }
        if(is_string($fields)){
            $sql .= '(`' . $fields . '`)';
            return $this->exec($sql);
        }
        return false;
    }

    abstract public function dropIndexConstraint($tablename, $indexname);

    public function addCheckConstraint($tablename, $condition, $checkName = NULL){
        if($checkName&&is_string($checkName)){
            $sql = sprintf("ALTER TABLE `%s` ADD CONSTRAINT %s CHECK (%s)", $tablename, $checkName, $condition);
        }else{
            $sql = sprintf("ALTER TABLE `%s` ADD CHECK (%s)", $tablename, $condition);
        }
        return $this->exec($sql);
    }

    public function dropCheckConstraint($tablename, $checkName){
        $sql = sprintf('ALTER TABLE `%s` DROP CONSTRAINT %s;', $tablename, $checkName);
        return $this->exec($sql);
    }

}