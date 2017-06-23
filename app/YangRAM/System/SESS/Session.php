<?php
namespace Tangram\SESS;

use RDO;
use Request;

/**
 *	Uniform User Passport
 *	统一用户护照
 *  单例类
 *	用户身份认证处理的全局对象
 */
final class Session {
    const
    QUERY_LESS = -1,
    QUERY_ALL = 0,
    QUERY_UID = 1,
    QUERY_USERNAME = 2,
    QUERY_EMAIL = 3,
    QUERY_MOBILEPHONE = 4,
    QUERY_UNICODENAME = 5;

    private static
    $sid,
    $handler = NULL;


    public static function init($sid=null){
        if(self::$handler===null){
            self::initSets();
            self::getHandler();
            session_set_save_handler(
			    [self::$handler, 'open'],
		        [self::$handler, 'close'],
		        [self::$handler, 'read'],
		        [self::$handler, 'write'],
		        [self::$handler, 'destroy'],
			    [self::$handler, 'gc']
    	    );
		    register_shutdown_function('session_write_close');
            session_name(_SESSION_NAME_);
            if($sid){
                session_id($sid);
            }
		    session_start();
            self::$sid = session_id();
        }
        return true;
	}

    private static function initSets(){
        ini_set('session.auto_start', 0);
		ini_set('session.session.gc_probability', 0);
        ini_set('session.use_trans_sid', 0);

    	ini_set('session.use_cookie', 1);
    	ini_set('session.cookie_path', '/');
	    ini_set('session.hash_bits_per_character', 5);
	    if(_SESIION_CROSS_&&_SESIION_DOMAIN_){
			ini_set('session.cookie_domain', _SESIION_DOMAIN_);
		}
	    ini_set('session.gc_probability', _SESIION_PROBAB_);
	    ini_set('session.gc_divisor', _SESIION_DIVISOR_);
		ini_set('session.gc_maxlifetime', _SESIION_EXPIRY_);
    }

    private static function getHandler(){
        include('NI_Session_interface.php');
		if(_SESSION_ON_DB_){
            include('Handlers/DbSession.php');
		    self::$handler = Handlers\DbSession::instance();
	    }else {
		    include('Handlers/FsSession.php');
			self::$handler = Handlers\FsSession::instance();
		}
    }

    public static function id(){
        return self::$sid;
    }

    public static function set($id, $data){
        self::$handler->write($id, $data);
    }

    public static function get($id){
        self::$handler->read($id);
    }

    public static function del($id){
        self::$handler->destroy($id);
    }
}
