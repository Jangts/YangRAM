<?php
namespace Tangram\ORM\traits;

use RDO;
use Status;
use Tangram\APP\ApplicationPermissions;

/**
 *	Basics Trait For Data Objects
 *	数据处理对象的基础特性
 */
trait common {

    protected static
    $initialized = false,
    $conns = NULL,
	$permissions = NULL;

    public static function initialize(ApplicationPermissions $permissions, array $conns){
		if(self::$initialized==false){
            self::$permissions = $permissions;
            self::$conns = $conns;
			self::$initialized = true;
            return true;
		}
        return false;
	}

    public static function stopAttack($type = 0) {
		$result = true;
		switch ($type) {
			case 3:
			foreach ($_GET as $key => $val) {
            	$_GET[$key] = self::filterSqlWords($val, QS_SCAN_GET);
        	}
			break;

			case 4:
			foreach ($_POST as $key => $val) {
                $_POST[$key] = self::filterSqlWords($val, QS_SCAN_POST);
            }
			break;

			case 6:
			foreach ($_COOKIE as $key => $val) {
                $_COOKIE[$key] = self::filterSqlWords($val, QS_SCAN_COOKIE);
            }
			break;
			
			default:
			foreach ($_GET as $key => $val) {
            	$_GET[$key] = self::filterSqlWords($val, QS_SCAN_GET);
        	}
			foreach ($_POST as $key => $val) {
                $_POST[$key] = self::filterSqlWords($val, QS_SCAN_POST);
            }
			foreach ($_COOKIE as $key => $val) {
                $_COOKIE[$key] = self::filterSqlWords($val, QS_SCAN_COOKIE);
            }
			break;
		}
    }

	public static function checkSqlWords($string, $type = QS_SCAN_GET){
        if(is_array($string)){
			$string = implode('<_TNI_GUARD_>', $string);
		}

		if($type == QS_SCAN_POST||$type == QS_SCAN_COOKIE){
			if (preg_match('/\\b(and|or)\\s.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)/is',$string,$matches) == 1){
				//var_dump($string, $matches);
				return false;
        	}
		}else{
			if (preg_match('\'|(and|or)\\s.+?(>|<|=|in|like)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)/is',$string) == 1){
				//var_dump($string, $matches);
				return false;
        	}
		}
		return true;
    }

	public static function filterSqlWords($string){
        if(is_array($string)){
			$string = implode('<_TNI_GUARD_>', $string);
		}

		$string = preg_replace('/\\b(and|or)\\s.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)/is','',$string);
		
		$array = explode('<_TNI_GUARD_>', $string);
		if(count($array)>1){
			return $array;
		}
		return trim($string);
    }

    private static function conn($options){
        if(is_numeric($options)&&isset(self::$conns[$options])){
            if(self::$conns[$options]['instance']){
                return self::$conns[$options]['instance'];
            }else{
                include_once(PATH_SYS.'ORM/Drivers/'.self::$conns[$options]['driver'].'.php');
			    $class = 'Tangram\ORM\Drivers\\'.self::$conns[$options]['driver'];
                return self::$conns[$options]['instance'] = $class::instance(self::$conns[$options]['options']);
            }
        }elseif(is_array($options)&&$options['driver']&&is_file(PATH_SYS.'ORM/Drivers/'.$options['driver'].'.php')){
            include_once(PATH_SYS.'ORM/Drivers/'.$options['driver'].'.php');
			$class = 'Tangram\ORM\Drivers\\'.$options['driver'];
            return $class::instance($options);
        }
        return NULL;
    }

    private static function escape($str){
        if(is_string($str)){
            return $str;
        }
        return '';
    }

    private static function tablename($str){
        if(preg_match("/^\w+$/", $str)){
            return '`' . $str . '`';
        }
        return self::escape($str);
    }

    private static function getQueryString($tables, $require = "1", $order = "1 ASC", $num = 0, $start = 0, $select = "*"){
        if(is_array($tables)){
            $first = "SELECT %s FROM `%s` WHERE %s";
            $after = " UNION ALL SELECT %s FROM `%s` WHERE %s";
			foreach($tables as $n=>$table){
                if(self::readable($table)){
                    if($n==0){
    					$sql = sprintf($first, $select, $table, $require);
    				}else{
    					$sql .= sprintf($after, $select, $table, $require);
    				}
                }else{
                    return false;
                }
			}
            if($order){
                $sql .= sprintf(" ORDER BY %s", $order);
            }
            if($num){
                $sql .= sprintf(" LIMIT %d, %d", $start, $num);
            }
			return $sql;
		}
		if(is_string($tables)){
            $sql = "SELECT %s FROM %s WHERE %s ORDER BY %s";
			$sql = sprintf($sql, self::escape($select), self::tablename($tables), self::escape($require), self::escape($order));
            if($num){
                $sql .= sprintf(" LIMIT %d, %d", $start, $num);
            }
			return $sql;
		}
		return false;
	}

    private static function get_permissions_code($table){
        if(!defined('TP_CURR')){
            return 2;
        }
        if(strpos($table, DB_AST) === 0||strpos($table, TP_CURR) === 0){
            return 2;
        }
        $permissions = self::$permissions;
        if($permissions->ALL_RDBTABLE_READABLE){
            if($permissions->ALL_RDBTABLE_WRITEABLE){
                return 2;
            }
            return 3;
        }
        if($permissions->ALL_RDBTABLE_WRITEABLE){
            return 1;
        }

        if(strpos($table, DB_SYS) === 0||strpos($table, DB_REG) === 0){
            if($permissions->SYSUSR_RDBTABLE_WRITEABLE){
                return 2;
            }
            return 3;
        }

        if(strpos($table, DB_MAP) === 0){
            if($permissions->MAPREG_RDBTABLE_WRITEABLE){
                return 2;
            }
            return 3;
        }

        if(strpos($table, DB_CNT) === 0||strpos($table, DB_SPC) === 0){
            if($permissions->CMFCNT_RDBTABLE_READABLE){
                if($permissions->CMFCNT_RDBTABLE_WRITEABLE){
                    return 2;
                }
                return 3;
            }
            if($permissions->CMFCNT_RDBTABLE_WRITEABLE){
                return 1;
            }
            return 0;
        }

        if(strpos($table, DB_SRC) === 0){
            if($permissions->SRCINF_RDBTABLE_READABLE){
                if($permissions->SRCINF_RDBTABLE_WRITEABLE){
                    return 2;
                }
                return 3;
            }
            if($permissions->SRCINF_RDBTABLE_WRITEABLE){
                return 1;
            }
            return 0;
        }

        if(strpos($table, DB_USR) === 0){
            if($permissions->USRMAP_RDBTABLE_READABLE){
                if($permissions->USRMAP_RDBTABLE_WRITEABLE){
                    return 2;
                }
                return 3;
            }
            if($permissions->USRMAP_RDBTABLE_WRITEABLE){
                return 1;
            }
            return 0;
        }

        if(strpos($table, DB_MSG) === 0){
            if($permissions->USRMSG_RDBTABLE_READABLE){
                if($permissions->USRMSG_RDBTABLE_WRITEABLE){
                    return 2;
                }
                return 3;
            }
            if($permissions->USRMSG_RDBTABLE_WRITEABLE){
                return 1;
            }
            return 0;
        }
        
        return 0;
    }

    private static function readable($table){
        $code = self::get_permissions_code($table);
        if($code>1){
            return true;
        }
        return new Status(706, '', 'Application ['.AI_CURR.'] has no access to read data from the table ['.$table.']', true);
    }

    private static function writeable($table){
        $code = self::get_permissions_code($table);
        if($code&&$code<3){
            return true;
        }
        return new Status(706, '', 'Application ['.AI_CURR.'] has no access to write data to the table ['.$table.']', true);
    }
}