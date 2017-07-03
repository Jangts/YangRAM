<?php
namespace Tangram\DBAL\Drivers;

use PDO;
use Status;
use Tangram\DBAL\NI_PDOExtended_BC;

/**
 *	PDO Extended For PostgreSQL
 */
class PostgreSQL extends NI_PDOExtended_BC {
    public static function parseDsn(array $options) {
        if(extension_loaded('PDO_PGSQL')){
            $dsn = sprintf('pgsql:host=%s;dbname=%s', $options['host'], $options['dbname']);
            if (!empty($options['hostport'])) {
                $dsn .= ';port=' . $options['hostport'];
            }
            return $dsn;
        }
        $status = new Status(797, '', 'Need SQL Driver PDO_PGSQL');
        $status->log();
    }

    public function showTables(){
        $sql = "select tablename as Tables_in_test from pg_tables where  schemaname ='public';";
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
        $sql             = 'select fields_name as "field",fields_type as "type",fields_not_null as "null",fields_key_name as "key",fields_default as "default",fields_default as "extra" from table_msg("' . $tableName . '");';
        $pdos            = $this->query($sql);
        $result          = $pdos->fetchAll(PDO::FETCH_ASSOC);
        $info            = [];
        if ($result) {
            foreach ($result as $key => $val) {
                $val                 = array_change_key_case($val);
                $info[$val['field']] = [
                    'name'    => $val['field'],
                    'type'    => $val['type'],
                    'notnull' => (bool) ('' !== $val['null']),
                    'default' => $val['default'],
                    'primary' => !empty($val['key']),
                    'autoinc' => (0 === strpos($val['extra'], 'nextval(')),
                ];
            }
        }
        return $this->fieldCase($info);
    }
    
    public function dropIndexConstraint($tablename, $indexname){
		$sql = sprintf('DROP INDEX %s;', $indexname);
        return $this->exec($sql);
	}
}
