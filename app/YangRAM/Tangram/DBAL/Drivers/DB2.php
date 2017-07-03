<?php
namespace Tangram\DBAL\Drivers;

use PDO;
use Status;
use Tangram\DBAL\NI_PDOExtended_BC;

/**
 *	PDO Extended For DB2
 */
class DB2 extends NI_PDOExtended_BC {
    public static function instance(array $options){
		if($dsn = self::parseDsn($options)){
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
                self::$instances[$id] = $obj->connectAndReturnInstance($dsn, NULL, NULL, $driverOptions, $dbname);
    		}
    		return self::$instances[$id];
        }
		return NULL;
	}

	public static function parseDsn(array $options) {
        if(extension_loaded('PDO_ODBC')){
            $dsn = sprintf('odbc:DRIVER={IBM DB2 ODBC DRIVER};HOSTNAME=%s;DATABASE=%s', $options['host'], $options['dbname']);
            if (!empty($options['hostport'])) {
                $dsn .= ';PORT=' . $options['hostport'];
            }
            if (empty($options['protocol'])) {
                $dsn .= ';PROTOCOL=TCPIP';
            }else{
                $dsn .= ';PROTOCOL=' . $options['protocol'];
            }
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
        $sql    = 'select tabname from syscat.tables where tabschema = current schema ;';
		$pdos   = $this->query($sql);
        $result = $pdos->fetchAll(PDO::FETCH_ASSOC);
        $info   = [];
        foreach ($result as $key => $val) {
            $info[$key] = current($val);
        }
        return $info;
    }

    public function getTableFields($tableName) {
        $sql    = "SELECT * FROM SYSCOLUMNS WHERE TBNAME='" . strtoupper($tableName) . "'";
        $pdos   = $this->query($sql);
        $result = $pdos->fetchAll(PDO::FETCH_ASSOC);
        $info   = [];
        if ($result) {
            foreach ($result as $key => $val) {
                $val                 = array_change_key_case($val, PDO::FETCH_ASSOC);
                $info[$val['NAME']] = [
                    'name'    => $val['NAME'],
                    'type'    => $val['COLTYPE'],
                    'notnull' => (bool) ('N' === $val['NULLS']),
                    'default' => $val['default'],
                    'primary' => (strtolower($val['key']) == 'pri'),
                    'autoinc' => (strtolower($val['extra']) == 'auto_increment')
                ];
            }
        }
        $sql    = "SELECT COLNAME FROM SYSCAT.KEYCOLUSE
                        WHERE CONSTNAME = (SELECT CONSTNAME FROM SYSCAT.TABCONST
                            WHERE TYPE = 'P' AND  TABNAME='" . strtoupper($tableName) . "');";
        $pdos   = $this->query($sql);
        $result = $pdos->fetchAll(PDO::FETCH_ASSOC);
        if ($result) {
            $info[$result['COLNAME']]['primary'] = true;
            $info[$result['COLNAME']]['autoinc'] = NULL;
        }
        return $info;
    }
    public function dropPrimaryKey($tablename, $primaryKeyName = NULL){
        $sql = sprintf('ALTER TABLE `%s` DROP PRIMARY KEY;', $tablename);
        return $this->exec($sql);
    }

    public function dropIndexConstraint($tablename, $indexname){
		$sql = sprintf('DROP INDEX %s;', $indexname);
        return $this->exec($sql);
	}
}
