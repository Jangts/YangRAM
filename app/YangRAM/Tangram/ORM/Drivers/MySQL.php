<?php
namespace Tangram\ORM\Drivers;

use PDO;
use Status;
use Tangram\ORM\NI_PDOExtended_BC;

/**
 *	PDO Extended For MySQL
 */
class MySQL extends NI_PDOExtended_BC {
	public static function parseDsn(array $options) {
		if(extension_loaded('PDO_MYSQL')){
			$dsn = sprintf('mysql:host=%s;dbname=%s', $options['host'], $options['dbname']);
	        if (!empty($options['hostport'])) {
	            $dsn .= ';port=' . $options['hostport'];
	        } elseif (!empty($options['socket'])) {
	            $dsn .= ';unix_socket=' . $options['socket'];
	        }
	        if (!empty($options['charset'])) {
	            $dsn .= ';charset=' . $options['charset'];
	        }
	        return $dsn;
		}
		$status = new Status(797, '', 'Need SQL Driver PDO_MYSQL');
        $status->log();
    }

	public function showTables($current = false){
        $sql    = $current ? 'SHOW TABLES FROM ' . $this->dbname : 'SHOW TABLES;';
		$pdos   = $this->query($sql);
        $result = $pdos->fetchAll(PDO::FETCH_ASSOC);
        $info   = [];
        foreach ($result as $key => $val) {
            $info[$key] = current($val);
        }
        return $info;
    }

	public function analyze($tablename){
		$sql = sprintf('ANALYZE TABLE `%s`;', $tablename);
        return $this->exec($sql);
    }

    public function checksum($tablename){
		$sql = sprintf('CHECKSUM TABLE `%s`;', $tablename);
        return $this->exec($sql);
    }

    public function check($tablename){
		$sql = sprintf('CHECK TABLE `%s`;', $tablename);
        return $this->exec($sql);
    }

    public function flush($tablename){
		$sql = sprintf('FLUSH TABLE `%s`;', $tablename);
        return $this->exec($sql);
    }

    public function optimize($tablename){
		$sql = sprintf('OPTIMIZE TABLE `%s`;', $tablename);
        return $this->exec($sql);
    }

	public function changeTableEngine($tablename, $engine){
		$sql = sprintf('ALTER TABLE `%s` ENGINE = %s;', $tablename, $engine);
        return $this->exec($sql);
    }

	public function getTableFields($tableName) {
		/*
        if (strpos($tableName, '.')) {
            $tableName = str_replace('.', '`.`', $tableName);
        }
		*/
        $sql    = 'SHOW COLUMNS FROM `' . $tableName . '`';
        $pdos   = $this->query($sql);
        $result = $pdos->fetchAll(PDO::FETCH_ASSOC);
        $info   = [];
        if ($result) {
            foreach ($result as $key => $val) {
                $val                 = array_change_key_case($val);
                $info[$val['field']] = [
                    'name'    => $val['field'],
                    'type'    => $val['type'],
                    'notnull' => (bool) ('' === $val['null']), // not null is empty, null is yes
                    'default' => $val['default'],
                    'primary' => (strtolower($val['key']) == 'pri'),
                    'autoinc' => (strtolower($val['extra']) == 'auto_increment')
                ];
            }
        }
        return $info;
    }

	public function addDefaultValue($tablename, $fieldName, $value){
        $sql = sprintf("ALTER TABLE `%s` ALTER `%s` SET DEFAULT '%s';", $tablename, $fieldName, $value);
        return $this->exec($sql);
    }

    public function dropDefaultValue($tablename, $fieldName){
        $sql = sprintf('ALTER TABLE `%s` ALTER `%s` DROP DEFAULT;', $tablename, $primaryKeyName);
        return $this->exec($sql);
    }

    public function dropPrimaryKey($tablename, $primaryKeyName = NULL){
        $sql = sprintf('ALTER TABLE `%s` DROP PRIMARY KEY;', $tablename);
        return $this->exec($sql);
    }

	public function setIncrement($tablename, $int = 1){
		$sql = sprintf('ALTER TABLE `%s` AUTO_INCREMENT= %d;', $tablename, $int);
        return $this->exec($sql);
    }

	public function dropForeignKey($tablename, $foreignKeyName){
        $sql = sprintf('ALTER TABLE `%s` DROP FOREIGN KEY %s;', $tablename, $foreignKeyName);
        return $this->exec($sql);
    }

	public function dropIndexConstraint($tablename, $indexname){
		$sql = sprintf('ALTER TABLE `%s` DROP INDEX %s;', $tablename, $indexname);
        return $this->exec($sql);
	}

	public function dropCheckConstraint($tablename, $checkName){
        $sql = sprintf('ALTER TABLE `%s` DROP CHECK %s;', $tablename, $checkName);
        return $this->exec($sql);
    }























    protected function getExplain($sql){
        $pdo    = $this->linkID->query("EXPLAIN " . $sql);
        $result = $pdo->fetch(PDO::FETCH_ASSOC);
        $result = array_change_key_case($result);
        if (isset($result['extra'])) {
            if (strpos($result['extra'], 'filesort') || strpos($result['extra'], 'temporary')) {
                Log::record('SQL:' . $this->queryStr . '[' . $result['extra'] . ']', 'warn');
            }
        }
        return $result;
    }
}
