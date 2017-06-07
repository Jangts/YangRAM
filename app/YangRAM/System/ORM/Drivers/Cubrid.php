<?php
namespace System\ORM\Drivers;

use PDO;
use Status;
use System\ORM\NI_PDOExtended_BaseClass;

/**
 *	PDO Extended For Cubrid
 */
class Cubrid extends NI_PDOExtended_BaseClass {
    public static function parseDsn(array $options) {
        if(extension_loaded('PDO_CUBRID')){
            $dsn = sprintf('cubrid:host=%s;dbname=%s', $options['host'], $options['dbname']);
            if (!empty($options['hostport'])) {
                $dsn .= ';port=' . $options['hostport'];
            }
            return $dsn;
        }
        $status = new Status(797, '', 'Need SQL Driver PDO_CUBRID');
        $status->log();
    }

    public function dropIndexConstraint($tablename, $indexname){
        return false;
	}
}
