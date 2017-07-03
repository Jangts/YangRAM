<?php
namespace Tangram\DBAL\Drivers;

use PDO;
use Status;
use Tangram\DBAL\NI_PDOExtended_BC;

/**
 *	PDO Extended For SQLServer With PDO_SQLSRV
 */
class SQLServer extends MSSQLServer {
    public static function parseDsn(array $options) {
        if(extension_loaded('PDO_SQLSRV')){
            $dsn = sprintf('sqlsrv:Server=%s;Database=%s', $options['server'], $options['dbname']);
            if (!empty($options['appname'])) {
                $dsn .= ';APP=' . $options['appname'];
            }
            if (!empty($options['ConnectionPooling'])) {
                $dsn .= ';ConnectionPooling=' . $options['ConnectionPooling'];
            }
            if (!empty($options['Encrypt'])) {
                $dsn .= ';Encrypt=' . $options['Encrypt'];
            }
            if (!empty($options['Failover_Partner'])) {
                $dsn .= ';Failover_Partner=' . $options['Failover_Partner'];
            }
            if (!empty($options['LoginTimeout'])) {
                $dsn .= ';LoginTimeout=' . $options['LoginTimeout'];
            }
            if (!empty($options['MultipleActiveResultSets'])) {
                $dsn .= ';MultipleActiveResultSets=' . $options['MultipleActiveResultSets'];
            }
            if (!empty($options['QuotedId'])) {
                $dsn .= ';QuotedId=' . $options['QuotedId'];
            }
            if (!empty($options['TraceFile'])) {
                $dsn .= ';TraceFile=' . $options['TraceFile'];
            }
            if (!empty($options['TraceOn'])) {
                $dsn .= ';TraceOn=' . $options['TraceOn'];
            }
            if (!empty($options['TransactionIsolation'])) {
                $dsn .= ';TransactionIsolation=' . $options['TransactionIsolations'];
            }
            if (!empty($options['TrustServerCertificate'])) {
                $dsn .= ';TrustServerCertificate=' . $options['TrustServerCertificate'];
            }
            if (!empty($options['WSID'])) {
                $dsn .= ';WSID=' . $options['WSID'];
            }
            return $dsn;
        }
        $status = new Status(797, '', 'Need SQL Driver PDO_SQLSRV');
        $status->log();
    }
}
