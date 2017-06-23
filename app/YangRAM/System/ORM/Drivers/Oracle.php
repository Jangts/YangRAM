<?php
namespace Tangram\ORM\Drivers;

use PDO;
use Status;
use Tangram\ORM\NI_PDOExtended_BaseClass;

/**
 *	PDO Extended For Oracle
 */
class Oracle extends NI_PDOExtended_BaseClass {
    public static function parseDsn(array $options) {
        if(extension_loaded('PDO_OCI')){
            if(empty($options['host'])){
                $dsn = 'oci:dbname='.$options['dbname'];
            }else{
                if(empty($options['hostport'])){
                    $dsn = 'oci:dbname='.$options['host'].'/'.$options['dbname'];
                }else{
                     $dsn = 'oci:dbname='.$options['host'].':'.$options['hostport'].'/'.$options['dbname'];
                }
            }
            if (!empty($options['charset'])) {
                $dsn .= ';charset=' . $options['charset'];
            }
            return $dsn;
        }
        $status = new Status(797, '', 'Need SQL Driver PDO_OCI');
        $status->log();
    }

    public function showTables(){
        $sql    = 'SELECT * FROM USER_TABLES;';
		$pdos   = $this->query($sql);
        $result = $pdos->fetchAll(PDO::FETCH_ASSOC);
        $info   = [];
        foreach ($result as $key => $val) {
            $info[$key] = current($val);
        }
        return $info;
    }

    public function getTableFields($tableName) {
        $sql    = "SELECT * FROM USER_TAB_COLUMNS WHERE TABLE_NAME= '".strtoupper($tableName)."';";
        $pdos   = $this->query($sql);
        $result = $pdos->fetchAll(PDO::FETCH_ASSOC);
        $info   = [];
        if ($result) {
            foreach ($result as $key => $val) {
                $val                       = array_change_key_case($val, CASE_UPPER);
                $info[$val['COLUMN_NAME']] = [
                    'name'    => $val['COLUMN_NAME'],
                    'type'    => $val['DATA_TYPE'],
                    'notnull' => (bool) ('N' === $val['NULLABLE']),
                    'default' => $val['DATA_DEFAULT'],
                    'primary' => false,
                    'autoinc' => false
                ];
            }
        }
        $sql    = "SELECT COLUMN_NAME FROM USER_CONS_COLUMNS
                        WHERE CONSTRAINT_NAME = (SELECT CONSTRAINT_NAME FROM USER_CONSTRAINTS
                            WHERE TABLE_NAME = '" . strtoupper($tableName) . "' AND CONSTRAINT_TYPE = 'P');";
        $pdos   = $this->query($sql);
        $result = $pdos->fetchAll(PDO::FETCH_ASSOC);
        if ($result) {
            $info[$result['COLUMN_NAME']]['primary'] = true;
            $info[$result['COLUMN_NAME']]['autoinc'] = NULL;
        }
        return $info;
    }

    public function dropIndexConstraint($tablename, $indexname){
		$sql = sprintf('DROP INDEX %s;', $indexname);
        return $this->exec($sql);
	}
}
