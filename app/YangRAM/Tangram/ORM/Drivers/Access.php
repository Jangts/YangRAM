<?php
namespace Tangram\ORM\Drivers;

use PDO;
use Status;
use Tangram\ORM\NI_PDOExtended_BC;


/**
 *	PDO Extended For Access
 */
class Access extends NI_PDOExtended_BC {
    public static function instance(array $options){
		if($dsn = self::parseDsn($options)){
            $id = \hash('md4', $dsn);
    		if(empty(self::$instances[$id])){
                if(isset($options['driverOptions'])){
                    $driverOptions = $options['driverOptions'];
                }else{
                    $driverOptions = [];
                }
    			$obj = new static;
                self::$instances[$id] = $obj->connectAndReturnInstance($dsn, NULL, NULL, $driverOptions, NULL);
    		}
    		return self::$instances[$id];
        }
		return NULL;
	}

	public static function parseDsn(array $options) {
        if(extension_loaded('PDO_ODBC')){
            $path = realpath($options['file']);
            $dsn = 'odbc:DRIVER={Microsoft Access Driver (*.mdb)}; DBQ='.$path;
            if (!empty($options['username'])) {
                $dsn .= ';UID=' . $options['username'];
            }
            if (!empty($options['password'])) {
                $dsn .= ';PWD=' . $options['password'];
            }
            return $dsn;
        }
        $status = new Status(797, '', 'Need SQL Driver PDO_ODBC');
        $status->log();
    }

    public function showTables(){
        $sql    = "SELECT name FROM MSYSNIDOECTS WHERE TYPE=1 AND NAME NOT LIKE 'Msys*'";
        $pdos   = $this->query($sql);
        $result = $pdos->fetchAll(PDO::FETCH_ASSOC);
        $info   = [];
        foreach ($result as $key => $val) {
            $info[$key] = current($val);
        }
        return $info;
    }

    public function addPrimaryKey($tablename, $field){
        $sql = sprintf('ALTER TABLE `%s` MODIFY `%s` NOT NULL;', $tablename, $field);
        $sql .= sprintf('ALTER TABLE `%s` ADD PRIMARY KEY (`%s`);', $tablename, $field);
        return $this->exec($sql);
    }

    public function addUnionPrimaryKey($tablename, array $fields){
        $sql = '';
        foreach ($fields as $field) {
            $sql .= sprintf('ALTER TABLE `%s` MODIFY `%s` NOT NULL;', $tablename, $field);
        }
        $sql .= sprintf('ALTER TABLE `%s` ADD PRIMARY KEY CLUSTERED (`%s`);', $tablename, join('`,`', $fields));
        return $this->exec($sql);
    }

    public function dropPrimaryKey($tablename, $primaryKeyName = NULL){
        $sql = sprintf('ALTER TABLE `%s` DROP PRIMARY KEY;', $tablename);
        return $this->exec($sql);
    }

    public function dropIndexConstraint($tablename, $indexname){
		$sql = sprintf('DROP INDEX %s ON %s;', $indexname, $tablename);
        return $this->exec($sql);
	}
}
