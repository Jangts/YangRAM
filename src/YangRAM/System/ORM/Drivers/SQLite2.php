<?php
namespace System\ORM\Drivers;

use PDO;
use Status;

/**
 *	PDO Extended For SQLite2
 */
class SQLite2 extends SQLite {
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
                self::$instances[$id] = $obj->connectAndReturnInstance($dsn, null, null, $driverOptions);
			}
			return self::$instances[$id];
		}
		return NULL;
	}

	public static function parseDsn(array $options) {
        if(extension_loaded('PDO_SQLITE')&&$path = realpath($options['file'])){
            $dsn = 'sqlite2:'.$path;
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
		$status = new Status(797, '', 'Need SQL Driver PDO_SQLITE');
        $status->log();
    }
}
