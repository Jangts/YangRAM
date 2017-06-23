<?php
namespace Tangram\R5;

use Tangram\NIDO\DataObject;
use Tangram\NIDO\Column;
use Tangram\NIDO\Parameters;
use Tangram\NIDO\FormData;

/**
 *	Universal Request Reader
 *	通用请求分析对象，单例类，其
 *  实例为一个封装的数据包，并未不限制调用者，只是恰好由统一资源索引器（$RUNTIME->RESOURCE）抢先实例化
 *	负责解读请求参数，懒读取模式节省消耗，只有当应用调用某数据时，读取器才为其解读
 */
final class Request {
    private static $instance = NULL;

	public static function instance(){
		if(self::$instance === NULL){
			self::$instance = new self;
		}
		return self::$instance;
	}

    public static function hostname(){
		if(_DOMAIN_SAFTY_){
            if(stripos(HOST, 'www.')===0){
                return 'www.'._DOMAIN_;
            }else{
                return _DOMAIN_;
            }
        }else{
            return HOST;
        }
	}
    
    private
    $headers = [],
    $data = [],
    $vals = [];


    private function __construct(){
        $data = [
            'HOST'          =>  self::hostname(),
            'ORIGIN_URI'    =>  $_SERVER['REQUEST_URI'],
        ];
		$PATH = preg_replace('/(^\/|\/$)/', '', preg_replace('/\/+/', '/', $_SERVER['PHP_SELF']));
        $path = strtolower($PATH);
        $PATH_ARRAY = explode('/', $PATH);
        $path_array = explode('/', $path);
        $homepages = explode('/', strtolower(_HOME_));

        $i18n = $GLOBALS['RUNTIME']->LANGS;

        if($i18n&&isset($path_array[1])&&in_array($path_array[1], $i18n)){
            $data['LANG'] = $path_array[1];
            array_shift($PATH_ARRAY);
            array_shift($path_array);
        }
        $data['uri_path'] = $path_array;
        
        if(count($data['uri_path'])===2&&in_array($data['uri_path'][1], $homepages)){
            $_SERVER['REQUEST_URI'] = '/';
            $data['TRANSLATED_URI'] = '/';
        }else{
            $path_array[0] = '';
            $data['TRANSLATED_URI'] = join('/', $path_array);
        }
        $PATH_ARRAY[0] = $data['TRANSLATED_URI'];
        if($_SERVER['QUERY_STRING']){
            $uri_array = explode('?', strtolower($_SERVER['REQUEST_URI']));
            $data['DIR'] = preg_replace('/\/$/', '',str_replace($data['TRANSLATED_URI'], '', $uri_array[0]));
            $data['QS'] = $_SERVER['QUERY_STRING'];
        }else{
            $data['DIR'] = preg_replace('/\/$/', '', str_replace($data['TRANSLATED_URI'], '',  strtolower(rawurldecode($_SERVER['REQUEST_URI'])))).'/';
            $data['QS'] = '';
        }
        define('HTTP_HOST', (_USE_HTTPS_ ? 'https://' : 'http://').HOST);
        define('HTTP_SOCKET', (_USE_HTTPS_ ? 'wss://' : 'ws://').HOST);
        define('HTTP_PID', HTTP_HOST.PID);
        define('__DIR', HTTP_HOST.$data['DIR']);
        define('__URL', HTTP_HOST.explode('?', $data['ORIGIN_URI'])[0]);
        define('__URI', HTTP_HOST.$data['ORIGIN_URI']);
        $data['METHOD'] = $_SERVER['REQUEST_METHOD'];
        $data['LENGTH'] = count($PATH_ARRAY);
        $data['URI_PATH'] = $PATH_ARRAY;
        $data['URI_HASH'] = md5($data['HOST'].$data['DIR'].$data['TRANSLATED_URI'].'?'.$data['QS']);
        $data['DIR'] .= '/';
        $this->data = $data;
    }

    public function __get($property){
        if(isset($this->data[$property])){
            return $this->data[$property];
        }
        if(isset($this->vals[$property])){
            return $this->vals[$property];
        }
        if($property=='IP'){
            return $this->data['IP'] = $this->getIP();
        }
        if($property=='ADDR'){
            return $this->data['ADDR'] = $this->getADDR();
        }
        if($property=='OS'){
            return $this->data['OS'] = $this->getOS();
        }
        if($property=='BROWSER'){
            return $this->data['BROWSER'] = $this->getBrowser();
        }
        if($property=='HEADERS'){
            return $this->data['HEADERS'] = join("\r\n", $this->getHeaders());
        }
        return NULL;
    }

    public function getIP() {
        if(array_key_exists('IP', $this->data)){
            return $this->data['IP'];
        }
		if(isset($_SERVER['HTTP_CDN_SRC_IP'])) {
			return $_SERVER['HTTP_CDN_SRC_IP'];
		} elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
			return $_SERVER['HTTP_CLIENT_IP'];
		} elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
			foreach ($matches[0] AS $xip) {
				if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
					return $xip;
				}
			}
		}
        return $_SERVER['REMOTE_ADDR'];
    }

    public function getADDR() {
        if(array_key_exists('ADDR', $this->data)){
            return $this->data['ADDR'];
        }
        if(isset($_SERVER['SERVER_ADDR'])){
			$SERVER_ADDR = $_SERVER['SERVER_ADDR'];
		}elseif(isset($_SERVER['LOCAL_ADDR'])){
			$SERVER_ADDR = $_SERVER['LOCAL_ADDR'];
		}else{
			$SERVER_ADDR = getenv('SERVER_ADDR');
		}
        return [
            'FROM'  =>  $_SERVER['REMOTE_ADDR'],
            'TO'    =>  $SERVER_ADDR
        ];
    }

    public function getOS(){
        if(array_key_exists('OS', $this->data)){
            return $this->data['OS'];
        }
		if(!empty($_SERVER['HTTP_USER_AGENT'])){
			$OS = $_SERVER['HTTP_USER_AGENT'];
			if (preg_match('/win/i',$OS)) {
				return 'Windows';
			}elseif (preg_match('/mac/i',$OS)) {
				return 'MAC';
			}elseif (preg_match('/linux/i',$OS)) {
				return 'Linux';
			}elseif (preg_match('/unix/i',$OS)) {
				return 'Unix';
			}elseif (preg_match('/bsd/i',$OS)) {
				return 'BSD';
			}else {
				return 'Other';
			}
		}
		return NULL;
	}

    public function getBrowser(){
        if(array_key_exists('BROWSER', $this->data)){
            return $this->data['BROWSER'];
        }
		if(!empty($_SERVER['HTTP_USER_AGENT'])){
			$br = $_SERVER['HTTP_USER_AGENT'];
			if (preg_match('/MSIE/i',$br)) {
				return 'MSIE';
			}elseif (preg_match('/Firefox/i',$br)) {
				return 'Firefox';
			}elseif (preg_match('/Chrome/i',$br)) {
				return 'Chrome';
			}elseif (preg_match('/Safari/i',$br)) {
				return 'Safari';
			}elseif (preg_match('/Opera/i',$br)) {
				return 'Opera';
            }elseif (preg_match('/YangRAM/i',$br)) {
				return 'YangRAM';
			}else {
				return 'Other';
			}
		}
        return NULL;
	}

    public function getHeaders(){
        if(array_key_exists('HEADERS', $this->data)){
            return $this->headers;
        }
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $this->headers[] = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5))))) . ":\s" . $value;
            }
        }
        return $this->headers;
    }

    public function update($item = NULL, $matches = NULL, $readonly = true){
        if(isset($this->data['PARAMS'])&&is_a($this->data['PARAMS'], '\Tangram\Parameters')){
            return $this;
        }
        if(!get_magic_quotes_gpc()){
            $_COOKIE = array_map("addslashes", $_COOKIE);
            $_GET = array_map("addslashes", $_GET);
            $_POST = array_map("addslashes", $_POST);
        }
        $lang = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5) : _LANG_;
        if(isset($_COOKIE['language'])&&preg_match('/^[a-z]{2}-[a-z]{2}$/', $_COOKIE['language'])){
            $lang = $_COOKIE['language']; 
        }
        if(defined('__REST_PARAM_INDEX')){
            $PATH_ARRAY = $this->data['URI_PATH'];
            $len = $this->data['LENGTH'] - (($this->data['LENGTH'] + __REST_PARAM_INDEX ) % 2);
            $REST_HANDLER_DIRS = [];
            $REST_PARAMS_ROW = [];
            $REST_PARAMS_ASSOC = [];
            if($len - __REST_PARAM_INDEX>1){
                for($i = 1; $i<__REST_PARAM_INDEX; $i++){
                    $REST_HANDLER_DIRS[] = $PATH_ARRAY[$i];
                }
                for($i = __REST_PARAM_INDEX; $i<$len; $i++){
                    $REST_PARAMS_ROW[] = $REST_PARAMS_ASSOC[$PATH_ARRAY[$i]] = $PATH_ARRAY[++$i];
                }
            }
            $this->data['REST_HANDLER_DIR'] = '/' . implode('/', $REST_HANDLER_DIRS);
            $this->data['REST_PARAMS'] = $REST_PARAMS_ROW;
            $args = $this->data['PARAMS'] = new Parameters($this->data['uri_path'], NULL, [], $REST_PARAMS_ASSOC);
        }else{
            $this->data['REST_HANDLER_DIR'] = '/';
            $this->data['REST_PARAMS'] = [];
            $args = $this->data['PARAMS'] = new Parameters($this->data['uri_path'], $item, $matches);
        }
        $vals = $args->toArray();
        $post = $this->data['FORM'] = new FormData($this->data['PARAMS'],$readonly);
        if(empty($this->data['LANG'])){
            if(isset($args->lang)){
                $lang = $args->lang;
            }elseif(isset($args->language)){
                $lang = $args->language;
            }
        
            if(isset($post->lang)){
                $lang = $post->lang;
            }elseif(isset($post->language)){
                $lang = $post->language;
            }
        }else{
            $lang = $this->data['LANG'];
        }
        
        $this->vals = array_merge($_COOKIE, $args->toArray(), $post->toArray());
        define('REQUEST_LANGUAGE', $this->data['LANGUAGE'] = $GLOBALS['RUNTIME']->LANGUAGE = $lang);
        return $this;
    }

    public function checkColumn($alternate = NULL){
        if(isset($this->data['COLUMN'])&&is_a($this->data['COLUMN'], '\Tangram\NIDO\Column')){
            return $this;
        }
        $column = $this->data['PARAMS']->column ? $this->data['PARAMS']->column : $alternate;
        $this->data['COLUMN'] = new Column($column);
        return $this;
    }

    public function toArray(){
        return array_merge($this->vals, $this->data['URI_PATH']);
    }

    public function toString(){
        $string = 'COOKIES: ' . DataObject::arrayToQueryString($_COOKIE);
        foreach($this->data as $index=>$item){
            if(is_scalar($item)){
                $string .= PHP_EOL.$index.': '.$item;
            }
            if(is_array($item)){
                $string .= PHP_EOL.$index.': '.DataObject::arrayToQueryString($item);
            }
            if(is_object($item)){
                $array = get_object_vars($item);
                $string .= PHP_EOL.$index.': '.DataObject::arrayToQueryString($array);
            }
        }
        return $string;
    }
}
