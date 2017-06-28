<?php
namespace Tangram\APP;

/**
 *	Sub Application Permissions
 *	子应用权限表
 *	记录着进程在当前时段是否可以使用敏感操作
 *	其状态可分为两段三态四个阶段：
 *  **  初始化时为全权状态，且所有权限可读可写（核心时段全权读写态）
 *  **  激活子应用后，由主控制器（Tangram\IDEA）根据当前子应用权限进行改写，此时权限因子应用而异，所有权限仍可重写（核心时段半权读写态）
 *  **  激活路由后，由资源索引器（Tangram\R5\ResourceIndexer）微调并改写权限表自身的可写权，所有权限不再可写（核心时段半权只读态）
 *  **  进入子应用时段，权限以上次微调为准，所有权限只读（应用时段半权只读态）
 */
final class ApplicationPermissions {
	private static $instance = NULL;

	public static function instance(){
		if(self::$instance === NULL){
			self::$instance = new self;
		}
		return self::$instance;
	}

	private
	$APP_RUN_LEVEL = 0,
	$ALL_PDOX_USEABLE    =   true,
    $DEFAULT_PDOX_USEABLE    =   true,
    $ACTIVITY_PDOX_USEABLE   =   true,

    $ALL_RDBTABLE_READABLE   =   true,
    $ALL_RDBTABLE_WRITEABLE  =   true,
    $SYSUSR_RDBTABLE_WRITEABLE   =   true,
    $MAPREG_RDBTABLE_WRITEABLE   =   true,
    $CMFCNT_RDBTABLE_READABLE    =   true,
    $CMFCNT_RDBTABLE_WRITEABLE   =   true,
    $SRCINF_RDBTABLE_READABLE    =   true,
    $SRCINF_RDBTABLE_WRITEABLE   =   true,
    $USRMAP_RDBTABLE_READABLE    =   true,
    $USRMAP_RDBTABLE_WRITEABLE   =   true,
    $USRMSG_RDBTABLE_READABLE    =   true,
    $USRMSG_RDBTABLE_WRITEABLE   =   true,
    $ALL_STORAGESG_READABLE  =   true,
    $ALL_STORAGESG_WRITEABLE =   true,

    $SYSDATA_READABLE  =   true,
    $SYSDATA_WRITEABLE =   true,
    $USRDATA_READABLE    =   true,
    $USRDATA_WRITEABLE   =   true,
    $MEMORYCACHE_USEABLE    =   true,

    $ALL_REMOTEURL_GETABLE  =   true,
    $ALL_REMOTEURL_SETABLE  =   true;

	private function __construct(){}


	public function __get($property){
		if(isset($this->$property)){
			return $this->$property;
		}else{
			return false;
		}
	}

	//__set()方法用来设置私有属性
	public function __set($property, $value){
		if($this->APP_RUN_LEVEL===0&&isset($this->$property)){
			if($property === 'APP_RUN_LEVEL'){
				$this->APP_RUN_LEVEL = intval($value);
			}else{
				$this->$property = !!$value;
			}
		}
	}
}
