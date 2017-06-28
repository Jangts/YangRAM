<?php
namespace Tangram\ORM\Drivers;

use PDO;
use Status;
use Tangram\ORM\NI_PDOExtended_BC;

/**
 *	PDO Extended For Informix
 */
class Informix extends NI_PDOExtended_BC {
    public static function parseDsn(array $options) {
        if(extension_loaded('PDO_INFORMIX')){
            $dsn = sprintf('informix:host=%s;database=%s', $options['host'], $options['dbname']);
            if (!empty($options['service'])) {
                $dsn .= ';service=' . $options['service'];
            }
            if (!empty($options['server'])) {
                $dsn .= ';server=' . $options['server'];
            }
            if (!empty($options['protocol'])) {
                $dsn .= ';protocol=' . $options['protocol'];
            }
            if (!empty($options['EnableScrollableCursors'])) {
                $dsn .= ';EnableScrollableCursors=' . $options['EnableScrollableCursors'];
            }
            return $dsn;
        }
        $status = new Status(797, '', 'Need SQL Driver PDO_INFORMIX');
        $status->log();
    }

    public function showTables(){
        $sql = "select tabname from systable;";
        $pdos   = $this->query($sql);
        $result = $pdos->fetchAll(PDO::FETCH_ASSOC);
        $info   = [];
        foreach ($result as $key => $val) {
            $info[$key] = current($val);
        }
        return $info;
    }

    public function getTableFields($fieldset) {
        //SELECT c.colname, c.coltype FROM syscolumns c, systables t WHERE c.tabid = t.tabid AND t.tabname = 'xxxTable'
        return [];
    }

    public function dropIndexConstraint($tablename, $indexname){
        return false;
	}
}
