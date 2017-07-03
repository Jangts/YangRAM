<?php
namespace Tangram\DBAL\Drivers;

use PDO;
use Status;
use Tangram\DBAL\NI_PDOExtended_BC;

/**
 *	PDO Extended For Firebird
 */
class Firebird extends NI_PDOExtended_BC {
    public static function parseDsn(array $options) {
        if(extension_loaded('PDO_FIREBIRD')){
            if(empty($options['host'])){
                $dsn = 'firebird:dbname='.$options['dbname'];
            }else{
                if(empty($options['hostport'])){
                    $dsn = 'oci:dbname='.$options['host'].':'.$options['dbname'];
                }else{
                     $dsn = 'oci:dbname='.$options['host'].'/'.$options['hostport'].':'.$options['dbname'];
                }
            }
            if (!empty($options['charset'])) {
                $dsn .= ';charset=' . $options['charset'];
            }
            if (!empty($options['role'])) {
                $dsn .= ';role=' . $options['role'];
            }
            return $dsn;
        }
        $status = new Status(797, '', 'Need SQL Driver PDO_FIREBIRD');
        $status->log();
    }

    public function showTables(){
        $sql    = 'SELECT RDB$RELATION_NAME AS TABLE_NAME FROM RDB$RELATIONS WHERE RDB$SYSTEM_FLAG = 0 AND RDB$VIEW_SOURCE IS NULL;';
		$pdos   = $this->query($sql);
        $result = $pdos->fetchAll(PDO::FETCH_ASSOC);
        $info   = [];
        foreach ($result as $key => $val) {
            $info[$key] = current($val);
        }
        return $info;
    }

    public function getTableFields($tableName) {
        //F.RDB$FIELD_LENGTH AS FieldLength,
        //CS.RDB$DEFAULT_COLLATE_NAME AS CharacterSet,
        $sql    = 'SELECT
                    RF.RDB$FIELD_NAME AS FieldName,
                    T.RDB$TYPE_NAME AS DataType,

                    RF.RDB$NULL_FLAG AS AllowNulls,

                    RF.RDB$DEFAULT_SOURCE AS Defaultvalue,
                    F.RDB$COMPUTED_SOURCE AS ComputedSource,
                    F.RDB$FIELD_SUB_TYPE AS SubType,
                    F.RDB$FIELD_PRECISION AS FieldPrecision
                    FROM RDB$RELATION_FIELDS RF
                    LEFT JOIN RDB$FIELDS F ON (F.RDB$FIELD_NAME = RF.RDB$FIELD_SOURCE)
                    LEFT JOIN RDB$TYPES T ON (T.RDB$TYPE = F.RDB$FIELD_TYPE)
                    LEFT JOIN RDB$CHARACTER_SETS CS ON (CS.RDB$CHARACTER_SET_ID = F.RDB$CHARACTER_SET_ID)
                    WHERE RF.RDB$RELATION_NAME = \'EMPLOYEE\' AND
                    T.RDB$FIELD_NAME = \'RDB$FIELD_TYPE\'
                    ORDER BY RF.RDB$FIELD_POSITION;';
        $pdos   = $this->query($sql);
        $result = $pdos->fetchAll(PDO::FETCH_ASSOC);
        $info   = [];
        if ($result) {
            foreach ($result as $key => $val) {
                $info[$val['FieldName']] = [
                    'name'    => $val['FieldName'],
                    'type'    => $val['DataType'],
                    'notnull' => (bool) (1 != $val['AllowNulls']),
                    'default' => $val['Defaultvalue'],
                    'primary' => false,
                    'autoinc' => false
                ];
            }
        }
        return $info;
    }

    public function dropIndexConstraint($tablename, $indexname){
        return false;
	}
}
