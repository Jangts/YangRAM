<?php
namespace Tangram\DBAL\Drivers;

use PDO;
use Status;
use Tangram\DBAL\NI_PDOExtended_BC;

/**
 *	PDO Extended For Cubrid
 */
class Cubrid extends NI_PDOExtended_BC {
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
