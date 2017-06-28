<?php
//配置文件
header("Content-Type: text/html; charset=UTF-8");
require $_SERVER["DOCUMENT_ROOT"]."/Tangram/Tangram.php";
require PATH_DRV."Logger/QQ".DLL;
 
$app_key = "101231537"; //QQ应用APP ID
$app_secret = "1ac20f054069bde7e01bda7293471775"; //QQ应用APP KEY
$callback_url = HTTP.USER_URL."API/QQ/Callback.php";
$scope='get_user_info,add_share'; //权限列表，具体权限请查看官方的api文档
?>