<?php
namespace Tangram\ORM\Drivers;

use PDO;
use Status;
use Tangram\ORM\NI_PDOExtended_BaseClass;

/**
 *	PDO Extended For Sybase
 */
class Sybase extends NI_PDOExtended_BaseClass {
    public static function parseDsn(array $options) {
        if(extension_loaded('PDO_DBLIB')){
            $dsn = sprintf('sybase:host=%s;dbname=%s', $options['host'], $options['dbname']);
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
        $sql    = "SELECT a.name,b.colid,b.name,c.name,b.usertype,b.length,
                    CASE WHEN b.status=0 THEN 'NOT NULL'
                    WHEN b.status=8 THEN 'NULL'
                    END status, d.text
                    FROM sysobjects a,syscolumns b,systypes c,syscomments d
                    WHERE a.id=b.id AND b.usertype=c.usertype AND a.type='U'
                    AND b.cdefault*=d.id
                    ORDER BY a.name,b.colid";
		$pdos   = $this->query($sql);
        $result = $pdos->fetchAll(PDO::FETCH_ASSOC);
        $info   = [];
        foreach ($result as $key => $val) {
            $info[$key] = current($val);
        }
        return $info;
    }

    public function addPrimaryKey($tablename, $field){
        $sql .= sprintf('ALTER TABLE `%s` MODIFY `%s` NOT NULL;', $tablename, $field);
        $sql .= sprintf('ALTER TABLE `%s` ADD PRIMARY KEY (`%s`);', $tablename, $field);
        return $this->exec($sql);
    }

    public function addUnionPrimaryKey($tablename, array $fields){
        $primaryKeyName = 'PK_'.$tablename;
        $sql = '';
        foreach ($fields as $field) {
            $sql .= sprintf('ALTER TABLE `%s` MODIFY `%s` NOT NULL;', $tablename, $field);
        }
        $sql .= sprintf('ALTER TABLE `%s` ADD CONSTRAINT %s PRIMARY KEY CLUSTERED (`%s`);', $tablename, $primaryKeyName, join('`,`', $fields));
        return $this->exec($sql);
    }

    public function dropPrimaryKey($tablename, $primaryKeyName = NULL){
        $sql = sprintf('ALTER TABLE `%s` DELETE PRIMARY KEY;', $tablename);
        return $this->exec($sql);
    }

    public function dropIndexConstraint($tablename, $indexname){
        return false;
	}
}
