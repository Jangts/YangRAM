<?php
namespace Tangram\DBAL\Drivers;

use PDO;
use Status;
use Tangram\DBAL\NI_PDOExtended_BC;

/**
 *	PDO Extended For SQLServer With PDO_DBLIB
 */
class MSSQLServer extends NI_PDOExtended_BC  {
    public static function parseDsn(array $options) {
        if(extension_loaded('PDO_DBLIB')){
            $dsn = sprintf('mssql:host=%s;dbname=%s', $options['host'], $options['dbname']);
            if (!empty($options['charset'])) {
                $dsn .= ';charset=' . $options['charset'];
            }
            if (!empty($options['appname'])) {
                $dsn .= ';appname=' . $options['appname'];
            }
            if (!empty($options['secure'])) {
                $dsn .= ';secure=' . $options['secure'];
            }
            return $dsn;
        }
        $status = new Status(797, '', 'Need SQL Driver PDO_DBLIB');
        $status->log();
    }

    public function showTables(){
        $sql = "SELECT TABLE_NAME
                FROM INFORMATION_SCHEMA.TABLES
                WHERE TABLE_TYPE = 'BASE TABLE';";
        $pdos   = $this->query($sql);
        $result = $pdos->fetchAll(PDO::FETCH_ASSOC);
        $info   = [];
        foreach ($result as $key => $val) {
            $info[$key] = current($val);
        }
        return $info;
    }

    public function getTableFields($tableName){
        list($tableName) = explode(' ', $tableName);
        $sql             = "SELECT   column_name,   data_type,   column_default,   is_nullable
        FROM    information_schema.tables AS t
        JOIN    information_schema.columns AS c
        ON  t.table_catalog = c.table_catalog
        AND t.table_schema  = c.table_schema
        AND t.table_name    = c.table_name
        WHERE   t.table_name = '$tableName'";
        $pdos            = $this->query($sql);
        $result          = $pdos->fetchAll(PDO::FETCH_ASSOC);
        $info            = [];
        if ($result) {
            foreach ($result as $key => $val) {
                $val                       = array_change_key_case($val);
                $info[$val['column_name']] = [
                    'name'    => $val['column_name'],
                    'type'    => $val['data_type'],
                    'notnull' => (bool) ('' === $val['is_nullable']), // not null is empty, null is yes
                    'default' => $val['column_default'],
                    'primary' => false,
                    'autoinc' => false,
                ];
            }
        }
        $sql = "SELECT column_name FROM information_schema.key_column_usage WHERE table_name='$tableName'";
        $pdos   = $this->query($sql);
        $result = $pdos->fetchAll(PDO::FETCH_ASSOC);
        if ($result) {
            $info[$result['column_name']]['primary'] = true;
            $info[$result['column_name']]['autoinc'] = NULL;
        }
        return $this->fieldCase($info);
    }

    public function dropIndexConstraint($tablename, $indexname){
		$sql = sprintf('DROP INDEX %s.%s;', $tablename, $indexname);
        return $this->exec($sql);
	}
}
