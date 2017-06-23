<?php
namespace Tangram\APP;

use Status;
use Tangram\ORM\NI_PDOExtended_BaseClass;
use RDO;
use Storage;
use Tangram\ClassLoader;
use Request;

/**
 *	Common Application Data Model
 *	通用应用数据模型
 *  应用数据模型的基类，封包了应用的基本信息
 *  并可以拓展应用的配置选项，并提供应用拓展信息的查询
 */
abstract class NI_Application_BaseModel {
    protected static
    $initialized = false,
    $PDOX = NULL,
    $public = [];

    final public static function setPDOX(NI_PDOExtended_BaseClass $PDOX){
        if(self::$initialized===false){
            self::$PDOX = $PDOX;
        }else{
            new Status(706.4, 'Initialized Alrady', 'Class Common Have Been Initialized.', true);
        }
		self::$initialized = true;
    }

    final public static function getPDOX(){
        return self::$PDOX;
    }

	final protected static function getDBAInfo($code){
		$rdo = new RDO;
		$result = $rdo->using(DB_SYS.'apps')->where('app_id', $code)->select('app_id, app_name, app_code, app_authorname, app_installpath, app_usedb');
		if($result&&$app = $result->getRow()){
			return [
				'APPID'		=>	$code,
				'ID'		=>	$app['app_id'],
				'Name'		=>	$app['app_name'],
				'Code'		=>	$app['app_code'],
				'Author'	=>	$app['app_authorname'],
				'DIR'		=>	preg_replace('/\/+/', '/', APP_DIR.$app['app_authorname'].'/'.$app['app_installpath'].'/'),
				'DBTPrefix'	=>	DB_APP.'a'.$code.'_',
				'CONN'		=>	$app['app_usedb']
			];
		}else{
			new Status(704.2, 'Application Not Found', 'No Suck Application.[APPID #'.$code.']', true);
		}
	}

	final protected static function getFSAInfo($code, $dir = I4S_DIR){
		$code = strtoupper($code);
		if(is_file(ROOT.$dir.'applications.json')&&($apps=json_decode(file_get_contents(ROOT.$dir.'applications.json'), true))){
			if(isset($apps[$code])){
				$app = $apps[$code];
				return [
					'APPID' 	=>	$app['id'],
					'ID' 		=>	$app['id'],
					'Name' 		=>	$app['name'],
					'Code'		=>	$app['code'],
					'Author'	=>	'YangRAM',
					'DIR' 		=>	$dir.$app['installpath'],
					'Agent'		=>	$app['agent'],
					'DBTPrefix'	=>	_DBPRE_,
					'CONN'		=>	0,
				];
			}else{
				new Status(704.1, 'Application Not Found', 'No Suck Application.[APPID #'.$code.']', true);
			}
		}else{
			new Status(702.1, '', 'Cannot Find Applications Map.', true);
		}
	}

    protected
	$appid,
	$code = -1,
	$data = [],
	$storage;

    final private function extendsProperties(){
		$cache = $this->storage;
		$data = $cache->take('appinfo');
		if(!$data){
			$data = $this->data;
			$props = new ApplicationProperties($data);
			$data['Props'] = $props->toArray();
			$data['NAMESPACE'] = '\\'.$data['Props']['Namespace'].'\\';
			$cache->store('appinfo', $data);
		}
		$data['Path'] = ROOT.$data['DIR'];
		ClassLoader::setNSMap([$data['Props']['Namespace'] =>	$data['Path']]);
		if($data['Props']['Suitspace']){
			$data['SuitPath'] = PATH_APP.$data['Author'].'/'.$data['Props']['Suitspace'].'/';
			ClassLoader::setNSMap([$data['Props']['Suitspace'] =>	$data['SuitPath']]);
		}
		return $this->data = $data;
	}

	final private function getBorthers(){
		$array = [];
		$table = DB_SYS.'apps';
		$classFile = 'app_authorname = '.$this->data['Props']['AuthorID'];
		$order = 'app_dev_id ASC';
		$result = SQL::getArray($table, $classFile, $order);
		foreach($result as $row){
			$array[$row["app_dev_id"]] = [
				'appid'	=>	$row["app_id"],
				'suit'	=>	$row["app_suit"],
				'name'	=>	$row["app_name"],
			];
		}
		return $array;
	}

    final protected function run($className){
		$GLOBALS['APPLICATION'] = $this;
		$GLOBALS['REQUEST'] = Request::instance();
		define('APPTIME', microtime(TRUE));
        new $className($this, $GLOBALS['REQUEST']);
	}

	final protected function runOnAnOtherProcess($className, $options = []){
		$communicator = new $className($this, Request::instance(), $options);
		if(isset($options['instr'])){
			$sender = $communicator->config($this->appid, $options['instr'], $options['method']);
			if(empty($options['timeout'])){
				$options['timeout'] = 30;
			}
			if(empty($options['params'])){
				$options['params'] = [];
			}
			if(empty($options['form'])){
				$options['form'] = false;
			}
			$sender->request($options['params'], $options['form'], $options['timeout']);
		}
		return $communicator;
	}

    protected function getinfo($code){
		return self::getFSAInfo($code);
	}
    

    final public function __construct($code){
		$code = strtoupper($code);
		$cache = new Storage(PATH_CACA.$code.'/', Storage::JSN, true);
		if($data = $cache->take('baseinfo')){
			$this->data = $data;
		}else{
			$this->data = $this->getinfo($code);
			$cache->store('baseinfo', $this->data);
		}
		$this->appid = $code;
		$this->storage = $cache;
    }
	
    final public function getAPPID(){
        return $this->appid;
    }

    final public function active(ApplicationPermissions $permissions){
		if(defined('AI_CURR')){
			return false;
		}
		$appdata = $this->extendsProperties();
		define('AI_CURR', $appdata['APPID']);
		define('TP_CURR', $appdata['DBTPrefix']);
		define('CI_CURR', $appdata['CONN']);
		define('AD_CURR', $appdata['DIR']);
		define('AP_CURR', $appdata['Path']);
		return $this->data['POWERS'] = $permissions;
	}

    final public function test(){
		$appdata = $this->data;
		$permissions = $appdata['POWERS'];

		$permissions->APP_WRITEABLE = 1;
        $permissions->APP_RUN_LEVEL = 1;
		if(is_file($classFile = $appdata['Path'].'Tester.php')){
			include($classFile);
			if(class_exists($className = $appdata['NAMESPACE'].'Tester')){
				$this->run($className);
				die;
			}
		}
	}

    final public function __set($name, $value){
        return false;
    }

	public function __get($name){
        if(isset($this->data[$name])){
            return $this->data[$name];
        }
		if($name==='Props'){
			$this->extendsProperties();
            return $this->data['Props'];
        }
		if($name==='Borthers'){
			$this->getBorthers();
            return $this->data['Borthers'];
        }
        return NULL;
    }

	public function get(array $options = [], $type = 'IPCommunicator'){
		$props = $this->Props;
		$appdata = $this->data;
		
		$classFile = $appdata['Path'].$props['ResHolders'][$type]['filename'];
		$className = $appdata['NAMESPACE'].$props['ResHolders'][$type]['classname'];
		if($type==='IPCommunicator'){
			if(is_file($classFile)){
				if(class_exists($className)){
					if(isset($options['instr'])){
						$options['method'] = 'get';
						return $this->runOnAnOtherProcess($className, $options);
					}
					return $this->runOnAnOtherProcess($className);
				}
			}
			return false;
		}
		$permissions = $appdata['POWERS'];
		$permissions->APP_WRITEABLE = 0;
        $permissions->APP_RUN_LEVEL = 1;
		if(is_file($classFile)){
			include($classFile);
		}else{
			new Status('708.0', 'Application '.$this->data['Name'].' Initialization Failure', 'Cannot Find Interface File [' . $classFile . ']', true, true);
		}
		if(class_exists($className)){
			$this->run($className);
		}else{
			new Status('705.0', 'Application '.$this->data['Name'].' Initialization Failure', 'Cannot Find Interface', true);
		}
	}

	public function set(array $options = [], $type = 'IPCommunicator'){
		include(PATH_NIAF.'Controllers/BaseSubmitter.php');
        class_alias('AF\Controllers\BaseSubmitter', 'Submitter');

		$props = $this->Props;
		$appdata = $this->data;
		$classFile = $appdata['Path'].$props['ResHolders'][$type]['filename'];
		$className = $appdata['NAMESPACE'].$props['ResHolders'][$type]['classname'];
		if($type==='IPCommunicator'){
			if(is_file($classFile)){
				if(class_exists($className)){
					if(isset($options['instr'])){
						$options['method'] = 'set';
						return $this->runOnAnOtherProcess($className, $options);
					}
					return $this->runOnAnOtherProcess($className);
				}
			}
			return false;
		}
		$permissions = $appdata['POWERS'];
		$permissions->APP_WRITEABLE = 1;
        $permissions->APP_RUN_LEVEL = 1;
		if(is_file($classFile)){
			include($classFile);
		}else{
			new Status(708.2, 'Interface File Not Found', 'Require Filename ['.$classFile.']', true, true);
		}
		if(class_exists($className)){
			$this->run($className);
		}else{
	        new Status(705.1, 'Interface Not Found', "Files of application [".$appdata['Name']."] on your i4s(YANGRAM INTERACTIVE INFORMATION INTELLIGENT INTEGRATION SYSTEM) may have been tampered.", true);
	    }
	}

    public function checkPermissions(){
        $PERMISSIONS = ApplicationPermissions::instance();
		return $this->ACTIVED_APP->active($PERMISSIONS);
	}
}