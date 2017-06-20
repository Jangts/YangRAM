<?php
// +-----------------------------------------------------------------------+
// | YangRAM [ Build What You Want By Using YangRAM Blocks ]               |
// +-----------------------------------------------------------------------+
// | Copyright (c) 2015-2017 http://www.yangram.com All rights reserved.   |
// +-----------------------------------------------------------------------+
// | Licensed ( http://nidn.yangram.com/licenses/YANGRAM-2.1 )             |
// +-----------------------------------------------------------------------+
// | Author  Jangts <ivanyeung@yangram.com>                                |
// +-----------------------------------------------------------------------+

namespace System;

use PDO;

/**
 *	Bus Control Unit (Intellective/Digital/Effective/Accurate)
 *	主控制器————智能的、数据化的、高效的、精准的
 *	由启动器调用的单例类，其唯一实例及YangRAM的全局变量$RUNTIME(运行时)
 *  PHP版本需求：>= PHP 5.4
 */
final class IDEA {
	const VERSION = '2.8.0';
	/**
	 * YangRAM的公开版本号，格式为
	 * {Major_Version_Number}.{Minor_Version_Number}.{Revision_Number}
	 * 其中
	 * ** 主版本号为营销意义上的版本号，以市场为主导来更改；
	 * ** 子版本号为实际意义上的版本号，反映版本的实际变动，
	 * ** *** 一个营销周期内（即主版本号不变的情况下），版本的变更由子版本号表现，
	 * ** *** 无论是主版本号变更，还是子版本号变更，都意味著YangRAM已经升级，其中主版本号的变更亦可称为迭代，
	 * ** *** 应尽力一代一版，最多不应超过四版，以保证版代差的稳定，同时采取主版本号跳号的方式来调整版代差；
	 * ** 修正号是在同一版本（子版本号为准）号下的更新号，是相对版本跳动所产生的变更较小的变更，多以修复bug和完善为主，
	 * ** *** 修正号正常情况采用累加法，但也会因为其他因素（如重大调整或营销噱头）跳号，
	 * ** *** 修正号变更代表YangRAM更新，会产生相应的更新补丁，开啓了自动更新的I4s会自动更新；
	 * ** *** 负责更改修正号的团队或个人需要呈交修正报告，中含修正原因、与上一次修正有哪些变动、与上一个版本有哪些变动等；
	 * ** *** 负责更改修正号的团队或个人同时负责製作补丁；
	 * ** *** 负责更改修正号的团队或个人对新补丁产生的错误和漏洞负责。
	**/

	const DEVID = '0';
	/**
	 * YangRAM的内部版本号，正式名为{YangRAM修正增删日志号}，其中
	 * ** 修，修改错误和漏洞，
	 * ** 正，优化逻辑和代码，
	 * ** 增，增加模块和功能，
	 * ** 删，删除模块和功能；
	 * 日志号用以记录YangRAM的每一次修改（包括架构/内核/标准库/系统应用等的修改），一般来说，
	 * ** 以工作日为周期，特殊情况下，如单日出现多次重大修正，或者同时存在多个分支（如两个团队同一时间分别进行着2.1版的维护和3.0的开发）时
	 * ** 允许一日记录多次，在数日没有实质进展的情况下，也
	 * ** 允许多日记录一次；
	 * 日志号用以记录实际修改次数，所以必需严格
	 * ** 使用累加法，不得跳号，同时为保证日志号得唯一性，
	 * ** 所有分支都应采用同一组日志号，即使这样会导致
	 * ** 连续的日志号不代表连续的修改；
	 * 只有特定的日志号会被公开，多数日志号不会被公开，因为
	 * ** 日志号的变更仅代表YangRAM团队的工作进度，不能代表YangRAM的更新进度，YangRAM的更新以修正号的变更为准；原则上，
	 * ** 纸有稳定而具有实际生产意义的修改才会同时产生日志号和修正号，并一同公开给外界，公开的完整的YangRAM版本格式为
	 * ** *** YangRAM {Major_Version_Number}.{Minor_Version_Number}.{Revision_Number} Dev {Dev_ID_Number}[, {Version Alias}]，如
	 * ** *** YangRAM 2.0.2 Dev 709 (此版没有别名，故省略{Version Alias})；
	 * 负责更改日志号的工作人员需要同时收集、整理、记录修改细则，必要时还要记录操作缘由；
	 * 日志号藉鉴了编译式程序的Build号，但是产生方式不大相同，且产生更加频繁。
	**/

	const MODE = 'DEVELOP';
	//const MODE = 'RELEASE';
	/**
	 * *DEVELOP* 开发模式
	 * ** 可用于测验流程设计和数据设计的合理性，重点在于测试框架和业务逻辑，不必过于强调UI等细节；
	 * ** 可用于检验功能是否可用、细节是否符合预期，以及在出现非预期情形时查验错误并修改之；
	 * ** 此模式下会强制开启技术员调试模式（即使你在配置表里关闭了调试模式）；
	 * *RELEASE* 发行模式
	 * ** 系统上线时的必需模式
	 * ** 此模式下仍可以开启争对一般技术员的调式模式，该模式下系统出现异常时技术员可以得到更详细的提示；
	 * ** 关闭调试模式时，系统遇到异常时会选择忽略错误，容错，或友好地中断（并暗地里记录问题）；
	 * ** 如何使用调试模式取决于开发者，YangRAM鼓励但不强制要求开发者去使用（虽然这个明显有利于开发者）。
	**/

	private static $instance, $isBoot = false;

	public
	$AA, //Active App
	$AP, //Application Permissions
	$RI, //resource indexer
	$LANGS;
	private $LANGUAGE;

	public function __construct(){
		IDEA::$instance === NULL or exit('Cannot Reinstance System');
		if (version_compare(PHP_VERSION, '5.4.0', '<') ) exit("Sorry, this version of YangRAM will only run on PHP version 5.4 or greater!\n");
		IDEA::$instance = $this;
		STAT\Status::init();
		ob_start();
		header('Content-Type: text/plain;charset=utf-8');
	}

	public function run_system(){
		define('NIKTIME', microtime(TRUE));
		$LOADER = ClassLoader::instance();
		$CONN = $this->load_constants();
		$this->init_data_operations($CONN);
		$this->init_system_components();
		if(strcasecmp(strtr($_SERVER['DOCUMENT_ROOT'], '\\', '/'), ROOT)==0){
			IDEA::$isBoot = true;
			return true;
		}
		$status = new STAT\Status(403);
		return $status->cast(STAT\Status::CAST_TLOG);
	}

	private function load_constants(){
		if(is_file(ROOT.'/configuration.ini')&&$config = json_decode(str_replace('<%D%>', PATH_DATA, file_get_contents(ROOT.'/configuration.ini')), true)){
			if(self::MODE==='DEVELOP'&&!defined('_USE_DEBUG_MODE_')){
				define('_USE_DEBUG_MODE_', true);
			}
			if(defined('_USE_DEBUG_MODE_')){
				unset($config['constants']['_USE_DEBUG_MODE_']);
			}
			if(defined('_DOMAIN_')){
				unset($config['constants']['_DOMAIN_']);
			}
			if(defined('_SETTER_')){
				unset($config['constants']['_SETTER_']);
			}
			if(defined('_TASKER_ENABLE_')){
				unset($config['constants']['_TASKER_ENABLE_']);
			}
			foreach($config['constants'] as $const=>$value){
				define($const, $value);
			}
			if(_USE_DEBUG_MODE_){
				ini_set("display_errors", "On");
			}
			ini_set('memory_limit', _MAXML_);
			define('DB_TMP',				_DBPRE_.'_tmp_');
			define('DB_SYS',				_DBPRE_.'_sys_');
			define('DB_REG',				_DBPRE_.'_reg_sys_');
			define('DB_MAP',				_DBPRE_.'_reg_map_');
			define('DB_CNT',				_DBPRE_.'_cnt_');
			define('DB_SPC',				_DBPRE_.'_reg_spc_');
			define('DB_SRC',				_DBPRE_.'_src_');
			define('DB_USR',				_DBPRE_.'_usr_map_');
			define('DB_MSG',				_DBPRE_.'_msg_');
			define('DB_APP',				_DBPRE_.'_app_');
			define('DB_AST',				_DBPRE_.'_ast_');
			$this->LANGUAGE = _LANG_;
			if(isset($config['i18n'])&&count($config['i18n'])){
				$this->LANGS = $config['i18n'];
			}
			return $config['rdbconnections'];
		}else{
			$status = new STAT\Status(701.1, '', 'Initialization Failure! Cannot Load Configure File.');
			return $status->cast(STAT\Status::CAST_TLOG);
		}
	}

	private function init_data_operations($connArray){
		define('QS_SCAN_GET', 		'get');
		define('QS_SCAN_POST', 	'post');
		define('QS_SCAN_COOKIE', 	'cookie');
		include(PATH_SYS.'CACH/traits/filesys.php');
		include(PATH_SYS.'NIDO/traits/conversion.php');
		include(PATH_SYS.'NIDO/traits/formatting.php');
		include(PATH_SYS.'ORM/traits/common.php');
		include(PATH_SYS.'ORM/traits/field.php');
		include(PATH_SYS.'ORM/traits/transaction.php');
		
		include(PATH_SYS.'CACH/Storage.php');
		include(PATH_SYS.'NIDO/DataObject.php');
		include(PATH_SYS.'NIDO/Column.php');
		include(PATH_SYS.'NIDO/Parameters.php');
		include(PATH_SYS.'NIDO/FormData.php');
		include(PATH_SYS.'ORM/NI_PDOExtended_BaseClass.php');
		include(PATH_SYS.'ORM/RDO.php');
		include(PATH_SYS.'ORM/RDOSelectResult.php');
		include(PATH_SYS.'ORM/Counter.php');

		include(PATH_SYS.'APP/Application.php');
		include(PATH_SYS.'APP/ApplicationProperties.php');
		include(PATH_SYS.'APP/ApplicationPermissions.php');

		class_alias('System\APP\Application', 'Application');
		class_alias('System\CACH\Storage', 'Storage');
		class_alias('System\ORM\RDO', 'RDO');
		class_alias('System\SESS\Session', 'SESS');
		class_alias('System\STAT\Status', 'Status');
		
		NIDO\Column::initialize();
		$AP = APP\ApplicationPermissions::instance();
		$CONNS = [];
		if(count($connArray)){
			foreach($connArray as $n => $conn){
				if($n){
					$CONNS[] = [
						'driver'	=>  $conn['driver'],
						'options'	=>	$conn,
						'instance'	=>	NULL
					];
				}else{
					include_once(PATH_SYS.'ORM/Drivers/'.$conn['driver'].'.php');
					$class = 'System\ORM\Drivers\\'.$conn['driver'];
					if($defaultpdox = $class::instance($conn)){
						$CONNS[] = [
							'driver'	=>  $conn['driver'],
							'options'	=>	$conn,
							'instance'	=>	$defaultpdox
						];
					}else{
						$status = new STAT\Status(797, 'Some needed pdo driver not found, please check your php on your web server.');
						return $status->cast(STAT\Status::CAST_TLOG);
					}
				}
			}
			$AP->DEFAULT_PDOX_USEABLE = true;
			ORM\RDO::initialize($AP, $CONNS);
			ORM\RDO::setPDOX(0);
			ORM\Counter::initialize($AP, $CONNS);
			$this->PERMISSIONS = $AP;
			return ;
		}elseif(defined('L')){
			$AP->APP_APDOX_GETABLE = false;
			$AP->APP_PDOXS_GETABLE = false;
			$this->PERMISSIONS = $AP;
		}else{
			$status = new STAT\Status(703.1, 'Default Database Not Found', 'Must set a default database connection configuration.');
			return $status->cast(STAT\Status::CAST_TLOG);
		}
	}

	private function init_system_components(){
		include(PATH_SYS.'R5/NI_ResourceIndexer_BaseClass.php');
		include(PATH_SYS.'R5/NI_ResourceHolder_BaseClass.php');
		include(PATH_SYS.'R5/RouteMapper.php');
		include(PATH_SYS.'R5/ResourceIndexer.php');
		include(PATH_SYS.'R5/Request.php');
		include(PATH_SYS.'R5/Response.php');
		include(PATH_SYS.'R5/RemoteDataReader.php');
		class_alias('System\R5\Request', 'Request');
		class_alias('System\R5\Response', 'Response');
		R5\RouteMapper::initialize();
		$this->RI = R5\ResourceIndexer::instance();
	}

	public function run_active_application(){
		if($this->AA===NULL){
			include(PATH_NIAF.'Models/BaseModel.php');
			include(PATH_NIAF.'Controllers/BaseCtrller.php');
			class_alias('AF\Models\BaseModel', 'Model');
			class_alias('AF\Controllers\BaseCtrller', 'Controller');
			$this->AA = new App\Application(AC_CURR);
			$AP = $this->AA->checkPermissions();
			if($AP->APP_PDOXS_GETABLE||$AP->APP_APDOX_GETABLE){
				if($PDOX = ORM\RDO::getPDOX(intval($this->AA->app_usedb))){
					APP\Application::setPDOX($PDOX);
				}
			}
			$this->RI->point($this->AA, $AP);
		}
	}

	private function pick_lang($filename, $pattern){
		$cxt = explode('{{lang}}', $pattern);
		$len = strlen($filename) - strlen(implode('', $cxt));
		$lan = substr($filename, strlen($cxt[0]), $len);
		$this->LANGUAGE = $lan;
		if(empty($lan)){
			return false;
		}
		return [$lan, $filename];
	}

	public function check_lang($pattern, $is_dir = false, $lang = false){
		if(empty($lang)||!is_string($lang)) {
			if(empty($this->LANGUAGE)||!is_string($this->LANGUAGE)){
				$lang = $this->LANGUAGE = _LANG_;
        	}else{
				$lang = $this->LANGUAGE;
			}
		}
		if($is_dir){
			$dir = str_replace('{{lang}}', $lang, $pattern);
			if(is_dir($dir)){
				$this->LANGUAGE = $lang;
				return [$lang, $dir];
			}		
		}else{
			$filename = str_replace('{{lang}}', $lang, $pattern);
			if(is_file($filename)){
				$this->LANGUAGE = $lang;
				return [$lang, $filename];
			}
		}
		$la = substr($lang, 0, 2);
		$files = glob(str_replace('{{lang}}', $la.'-*', $pattern));
		
		if(isset($files[0])){
			return $this->pick_lang($files[0], $pattern);
		}
		if($lang!==$this->LANGUAGE){
			return $this->check_lang($pattern, $is_dir, $this->LANGUAGE);
		}
		return false;
	}

	public function log($type, $text){
		if(is_string($type)&&preg_match('/^\w+$/',$type)){
			$path = PATH_CAC_LOG.'notes/'.$type.'/'.date('Ym').'/';
			$filename = $path.date('Ymd');
			//$text = # 需要对text进行检查;
			if (!file_exists($path)){
				mkdir($path, 0777, true);
			}
			$file = @fopen($filename, 'a') or new STAT\Status(706.3, 'Permission Denied', 'Unable to write run log! The current log file may be read-only.', true);
			fwrite($file, $text);
			fclose($file);
		}
		return false;
	}

	final public function __get($name){
		if($name==='LANGUAGE'){
			if(empty($this->LANGUAGE)||!is_string($this->LANGUAGE)){
				return $this->LANGUAGE = _LANG_;
			}
			return $this->LANGUAGE;
		}
		return null;
    }

    final public function __set($name, $value){
        if($name==='LANGUAGE'){
			if(!empty($value)&&is_string($value)){
				$this->LANGUAGE = $value;
			}
		}
		return $value;
    }
}


include(PATH_SYS.'ClassLoader.php');
include(PATH_SYS.'SESS/Session.php');
include(PATH_SYS.'STAT/Status.php');
class_alias('System\IDEA', 'IDEA');