<?php
namespace AF\Models\Util;

use RDO;
use AF\Models\R3Model_BC;

/**
 *	GuestRecord
 *	作客对象
 *  协约单例类（非技术单例类）
 *	注册用户与普通访客来访登记和访问信息读取所用的全局对象
 */
final class GSTI extends R3Model_BC {
    const
    DAILY = 1,
    WEEKLY = 7,
    MONTHLY = 30,
    QUARTERLY = 90,
    YEARLY = 365,

    ALL = 0,
    IS_MOBILE = 1,
    IS_NEWER = 2,
    IS_NEW_MOBILE = 3,
    NO_MOBILE = 4,
    IS_OLDER = 5,
    
    PV = 0,
    IP = 1,
    UV = 2,
    UR = 3;

    protected static
	$ca_path = '',
    $table = DB_AST.'users_guests',
    $indexes = ['id'],
    $aikey = 'id',
    $defaults = [
        'id'    =>  0,
        'usr_id' => 0,
		'gst_id' => 0,
        'app_id' => 10,
		'col_id' => 1,
        'uri' => '',
        'accesstime' => DATETIME,
        'is_mobile' => 0,
        'ip' => '',
        'is_new' => 0,
        'source' => NULL
    ];

    public static function statistics($range = self::DAILY, $filter = self::ALL, $select = self::PV, $timestamp = false){
        $timestamp = is_numeric($timestamp) ? $timestamp : time();
        $rdo = new RDO;
		$rdo->using(DB_AST.'users_guests');
        switch($filter){
            case self::IS_MOBILE:
            $where_suffix = ' AND is_mobile = 1';
            break;
            case self::IS_NEWER:
            $where_suffix = ' AND is_new = 1';
            break;
            case self::IS_NEW_MOBILE:
            $where_suffix = ' AND is_mobile = 1 AND is_new = 1';
            break;
            case self::NO_MOBILE:
            $where_suffix = ' AND is_mobile = 0';
            break;
            case self::IS_OLDER:
            $where_suffix = ' AND is_new = 0';
            break;
            default:
            $where_suffix = '';
        }
        if($select === self::UR){
            $where_suffix .= ' AND usr_id > 0';
        }
        switch($range){
            case self::MONTHLY:
            $rdo->requiring("DATE_FORMAT(accesstime, '%Y-%m') = '".date("Y-m", $timestamp)."'".$where_suffix);
            break;
            case self::YEARLY:
            $rdo->requiring("DATE_FORMAT(accesstime, '%Y') = '".date("Y", $timestamp)."'".$where_suffix);
            break;
            default:
            $rdo->requiring("DATE_FORMAT(accesstime, '%Y-%m-%d') = '".date("Y-m-d", $timestamp)."'".$where_suffix);
        }
        switch($select){
            case self::IP:
            return $rdo->count('DISTINCT ip');
            break;
            case self::UR:
            return $rdo->count('DISTINCT usr_id');
            break;
            case self::UV:
            return $rdo->count('DISTINCT gst_id');
            break;
            default:
            return $rdo->count('id');
        }
    }

    public static function hours($date, $start, $end, $is_new = NULL){
        $rdo = new RDO;
        $rdo->using(DB_AST.'users_guests');
        if($is_new===true||$is_new===1){
            $require = "accesstime >= '".$date." ".$start."' AND accesstime < '".$date." ".$end."' AND is_new = 1";
        }else{
            if($is_new===NULL){
                $require = "accesstime >= '".$date." ".$start."' AND accesstime < '".$date." ".$end."'";
            }else{
                $require = "accesstime >= '".$date." ".$start."' AND accesstime < '".$date." ".$end."' AND is_new = 0";
            }
        }
        return [
            'PV' => $rdo->requiring($require)->count('id'),
            'IP' => $rdo->requiring($require)->count('DISTINCT ip'),
            'UV' => $rdo->requiring($require)->count('DISTINCT gst_id')
        ];
    }
}
