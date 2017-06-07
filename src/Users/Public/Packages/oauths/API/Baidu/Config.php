<?php
//配置文件
header("Content-Type: text/html; charset=UTF-8");
require $_SERVER["DOCUMENT_ROOT"]."/Tangram/Tangram.php";
require PATH_DRV."Logger/Baidu".DLL;
 
$app_key = "";
$app_secret = "";
$callback_url = HTTP.USER_URL."API/Baidu/Callback.php";
?>