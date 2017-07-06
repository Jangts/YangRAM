<?php
$____SAPINAME = php_sapi_name();
if(preg_match("/cli/i", $____SAPINAME)){
    if($_SERVER['argc']>1){
        $_SERVER['SERVER_SOFTWARE'] = $____SAPINAME;
        $_SERVER['HTTP_HOST'] = $_SERVER['REMOTE_HOST'] = $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
        $_SERVER['SERVER_PORT'] = '80';
        $_SERVER['PHP_SELF'] = '/' . $_SERVER['argv'][0] . '/' . $_SERVER['argv'][1];
        if($_SERVER['argc'] > 2&&$_SERVER['argv'][2]){
            // $_SERVER['QUERY_STRING'] = str_replace('+', ' ', $_SERVER['argv'][2]);
            $_SERVER['QUERY_STRING'] = $_SERVER['argv'][2];
            $____QS = '?'.$_SERVER['QUERY_STRING'];
        }else{
            $_SERVER['QUERY_STRING'] = $____QS = '';
        }
        $_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'] . $____QS;
        if($_SERVER['argc'] > 3&&$_SERVER['argv'][3]){
            $_SERVER['REQUEST_METHOD'] = $_SERVER['argv'][3];
        }else{
            $_SERVER['REQUEST_METHOD'] = 'GET';
        }     
        include 'boot.php';
    }
    
}else{
    // header('Content-Type:text/plain;charset=UTF-8');
    // var_dump($_SERVER);
    // exit;
    include 'boot.php';
}
