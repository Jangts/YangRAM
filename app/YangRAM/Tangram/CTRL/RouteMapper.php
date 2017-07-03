<?php
namespace Tangram\CTRL;
use Storage;
use RDO;

/**
 *	Uniform Routemap Builder
 *	统一自定义路由表生成器，根据路由表ID，
 *  生成并缓存该路由表对应的正则模式，以供统一资源索引器（$RUNTIME->RESOURCE）进行分析
 */
final class RouteMapper {
    protected static
    $storage = NULL,
    $rdo = NULL;

    public static function initialize(){
        self::$storage = new Storage(PATH_DAT_RMAP, Storage::JSN, true);
        self::$storage->setBefore(str_replace(':', '\\', HOST).'/r')->useHashKey (false)->setAfter ('.json');
    }

    private static function setConn(){
        self::$rdo = new RDO;
        self::$rdo->using(DB_MAP.'routepatterns');
    }

    public static function getPatterns($mapid){
        if($data = self::$storage->take('map_'.$mapid)){
            return $data;
        }else{
            return self::updatePatterns($mapid);
        }
    }

    public static function updatePatterns($mapid){
        $data = self::queryPatterns($mapid);
        self::$storage->store('map_'.$mapid, $data);
        return $data;
    }

    private static function queryPatterns($mapid){
        self::$rdo != NULL or self::setConn();
        $result = self::$rdo->where('MAP_ID', $mapid)->where('KEY_STATE', 1)->select();
        $data = [];
        if($result){
            $list = self::resortPatterns($result->getPDOStatement());
            foreach($list as $item){
                $item['PATTERN'] = self::replacePattern($item['PATTERN']);
                $item['DIR_ALIASES'] = strlen($item['DIR_ALIASES'])>0 ? explode(',', $item['DIR_ALIASES']) : [];
                $item['PRM_NAMES'] = strlen($item['PRM_NAMES'])>0 ? explode(',', $item['PRM_NAMES']) : [];
                $item['DOMAINS'] = strlen($item['DOMAINS'])>0 ? explode(',', $item['DOMAINS']) : [];
                $item['DEFAULTS'] = self::assignDefaults($item);
                $data[] = $item;
            }
        }
        return $data;
    }

    private static function resortPatterns(\PDOStatement $pdos){
        $list = [];
		$sort = 0;
		$type = 9;
		while($row = $pdos->fetch(\PDO::FETCH_ASSOC)){
			if($row['TYPE']==$type){
				$sort ++;
			}else{
				$type = $row['TYPE'];
				$sort = 1;
			}
            $row['SORT'] = $sort;
            $list[] = $row;
			self::$rdo->where('id', $row['ID'])->update($row);
		}
        return $list;
	}

	private static function replacePattern($str){
		if(substr($str,0,1)=='/') {
			$str = HOST.$str;
		}
		$str = preg_replace('/(\/|\\|\+|\?)/', '\\\\$1', $str);
		$str = str_replace('.', '\.', $str);
		$str = str_replace('*', '\S*', $str);
		$str = str_replace('<a>', '([A-z]+)', $str);
		$str = str_replace('<0>', '([0-9]+)', $str);
		$str = str_replace('<A9>', '([A-z0-9]+)', $str);
		$str = str_replace('<w>', '([A-z0-9-_\.]+)', $str);
		$str = str_replace('<u>', '([^\\\\\/\r\n]+)', $str);
		$str = preg_replace('/^\S*\/$/', '$0*', $str);
		$arrStr = explode('\/\S', $str);
		if($arrStr[count($arrStr)-1] == '*') {
			$arrStr[count($arrStr)-1] = '(\/*|\/+\S*)';
			$str = implode('', $arrStr);
		}
		return '/^'.$str.'$/i';
	}

    private static function assignDefaults(array $route){
		$defaults = '';
		if($route['HLD_OK']){
			$defaults .= "ok:".$route['HLD_OK'];
		}
		if($route['COL_ALIAS']){
			$defaults .= ", column:".$route['COL_ALIAS'];
		}
		if(!empty($route['SET_ALIAS'])){
			$defaults .= ", preset:".$route['SET_ALIAS'];
		}
		if(!empty($route['GRP_CODE'])){
			$defaults .= ", contentgroup:".$route['GRP_CODE'];
		}
		if(preg_match('/^\d+$/', $route['CAT_ID'])){
			$defaults .= ", category:".$route['CAT_ID'];
		}
		if($route['DEFAULTS']){
			$defaults .= ", ".$route['DEFAULTS'];
		}
		if(strlen($defaults)>0&&strstr($defaults,':')){
			$defaults = '{"'.$defaults.'"}';
			$defaults = preg_replace('/(:[\s]{0,}|,[\s]{0,})/', '"$1"', $defaults);
			return json_decode($defaults, true);
		}
		return [];
	}

    public static function getDirnames(){
        if($data = self::$storage->take('dirs')){
            return $data;
        }else{
            return self::updateDirnames();
        }
    }

    public static function updateDirnames(){
        $rdo = new RDO;
        $rdo->using(DB_MAP.'routedirs');
		$result = $rdo->requiring(1)->select();
        $data = [];
        if($pdos = $result->getPDOStatement()){
            while($row = $pdos->fetch(\PDO::FETCH_ASSOC)){
				$dirname = preg_replace('/(^\/|\/$)/', '', preg_replace('/[\\\\\/]+/', '/', $row['DIR_NAME']));
				if($dirname){
					$dirname = '/' . $dirname;
					$fulldir = str_replace('<ANY>', strtolower(HOST), $row['DOMAIN']).$dirname . '/';
					if($row['PSI']===NULL){
						$row['PSI'] = count(explode('/', $dirname));
					}
					$data[$fulldir] = [
						'HDL_ID'	=>	$row['HDL_ID'],
						'LENGTH'	=>	$row['PSI']
					];
				}
			}
        }
        self::$storage->store('dirs', $data);
        return $data;
    }

	public static function emptyCache(){
        self::$storage->cleanOut();
    }
}
