<?php
namespace Tangram\CTRL;

use Tangram\IDEA;
use PDO;
use Status;
use RDO;
use Storage;

include 'NI_Application_BC.php';

/**
 *	Common Application Data Model
 *	通用应用数据模型
 *  应用数据模型的基类，封包了应用的基本信息
 *  并可以拓展应用的配置选项，并提供应用拓展信息的查询
 */
final class Application extends NI_Application_BC {
	public static function all(){
		$objs = [];
        $rdo = new RDO;
		$result = $rdo->using(DB_SYS.'apps')->select('app_id, app_name, app_code, app_authorname, app_installpath, app_usedb, app_version');
        if($result = $rdo->using(DB_REG.'columns')->select()){
            $pdos = $result->getPDOStatement();
            while($app = $pdos->fetch(PDO::FETCH_ASSOC)){
				$data = [
					'APPID'		=>	$app['app_id'],
					'ID'		=>	$app['app_id'],
					'Name'		=>	$app['app_name'],
					'Code'		=>	$app['app_code'],
					'Version'	=>	$app['app_version'],
					'Author'	=>	$app['app_authorname'],
					'DIR'		=>	($code>1000) ? APP_DIR.$app['app_authorname'].'/'.$app['app_installpath'].'/' : I4S_DIR.$app['app_installpath'].'/',
					'DBTPrefix'	=>	DB_APP.'a'.$code.'_',
					'CONN'		=>	$app['app_usedb'],
				];
                $cache = new Storage(PATH_CACA.$app['app_id'].'/', Storage::JSN, true);
				$cache->store('baseinfo', $data);
                $obj = new self($app['app_id']);
                $objs[] = $obj;
            }
        }
        return $objs;
    }

    protected function getinfo($code){
		if(is_numeric($code)){
			if($code>=8888&&$code<=8899){
				return self::getFSAInfo($code, TPA_DIR);
			}
			return self::getDBAInfo($code);
		}else{
			return self::getFSAInfo($code);
		}
	}
	
	public function checkPermissions(){
		$PERMISSIONS = ApplicationPermissions::instance();
		$PERMISSIONS = $this->active($PERMISSIONS);
		if(RT_CURR!=='UT'&&is_numeric($this->appid)&&$this->appid>1000){
			$storage = new Storage(PATH_DAT_PMSN, Storage::JSN, true);
			$storage->setBefore()->useHashKey(false)->setAfter('.json');
			$cache = $storage->get($this->appid);
			$data = $cache->read();
			if($data==false){
				$data = $cache->write([
					"ALL_PDOX_USEABLE"			=>	false,
					"DEFAULT_PDOX_USEABLE"		=>	false,
					"ACTIVITY_PDOX_USEABLE"		=>	false,

					"ALL_RDBTABLE_READABLE"		=>	false,
					"ALL_RDBTABLE_WRITEABLE"	=>	false,
					"SYSUSR_RDBTABLE_WRITEABLE"	=>	false,
					"MAPREG_RDBTABLE_WRITEABLE"	=>	false,
					"CMFCNT_RDBTABLE_READABLE"	=>	false,
					"CMFCNT_RDBTABLE_WRITEABLE"	=>	false,
					"SRCINF_RDBTABLE_READABLE"	=>	false,
					"SRCINF_RDBTABLE_WRITEABLE"	=>	false,
					"USRMAP_RDBTABLE_READABLE"	=>	false,
					"USRMAP_RDBTABLE_WRITEABLE"	=>	false,
					"USRMSG_RDBTABLE_READABLE"	=>	false,
					"USRMSG_RDBTABLE_WRITEABLE"	=>	false,

					"ALL_STORAGESG_READABLE"	=>	false,
					"ALL_STORAGESG_WRITEABLE"	=>	false,
					"SYSDATA_READABLE"			=>	false,
					"SYSDATA_WRITEABLE"			=>	false,
					"USRDATA_READABLE"			=>	false,
					"USRDATA_WRITEABLE"			=>	false,
					"MEMORYCACHE_USEABLE"		=>	false,

					"ALL_REMOTEURL_GETABLE"		=>	false,
					"ALL_REMOTEURL_SETABLE"		=>	false
				]);
			}
			foreach ($data as $key => $value) {
				$PERMISSIONS->$key = $value;
			}
		}
		return $PERMISSIONS;
	}
}