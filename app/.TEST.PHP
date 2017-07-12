<?php
define('_TESTER_', '/.TEST.PHP/');
define('_SAPI_NAME_', php_sapi_name());
if(preg_match("/cli/i", _SAPI_NAME_)){
    define('_TEST_MODE_', 2);
    if($_SERVER['argc']>2){
        function parseQueryString($string){
            $minarr = explode('&', str_replace('+', ' ', $_SERVER['QUERY_STRING']));
            $gets = [];
            foreach($minarr as $item){
                if($item){
                    $kvarr = explode('=', $item);
                    $key = array_shift($kvarr);
                    $gets[$key] = implode('=', $kvarr);
                }
            }
            return $gets;
        }
        $_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__);
        $_SERVER['SERVER_SOFTWARE'] = _SAPI_NAME_;
        $_SERVER['HTTP_HOST'] = $_SERVER['REMOTE_HOST'] = $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
        $_SERVER['SERVER_PORT'] = '80';
        $_SERVER['PHP_SELF'] = '/' . $_SERVER['argv'][0] . '/' . $_SERVER['argv'][1];
        if($_SERVER['argc'] > 2&&$_SERVER['argv'][2]){
            $_SERVER['QUERY_STRING'] = $_SERVER['argv'][2];
            $____QS = '?'.$_SERVER['QUERY_STRING'];
            $_GET = parseQueryString($_SERVER['QUERY_STRING']);
        }else{
            $_SERVER['QUERY_STRING'] = $____QS = '';
        }
        $_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'] . $____QS;
        if($_SERVER['argc'] > 3&&$_SERVER['argv'][3]){
            $_SERVER['REQUEST_METHOD'] = strtoupper($_SERVER['argv'][3]);
            if($_SERVER['REQUEST_METHOD']==='PUT'||$_SERVER['REQUEST_METHOD']==='POST'){
                fwrite(STDOUT,"Please input the formdata:\r\n");
                $_POST = json_decode(fgets(STDIN), true);
                if(!$_POST){
                    $_POST = [];
                } 
            }
        }else{
            $_SERVER['REQUEST_METHOD'] = 'GET';
        }
        include 'boot.php';
    }
}else{
    define('_TEST_MODE_', 1);
    include 'boot.php';
}
