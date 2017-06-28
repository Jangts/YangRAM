<?php
//配置文件
header("Content-Type: text/html; charset=UTF-8");
require $_SERVER["DOCUMENT_ROOT"]."/Tangram/Tangram.php";
require PATH_DRV."Logger/Kaixin".DLL;
 
$app_key = "313685638118e30b2c7edb9306fca725";
$app_secret = "a1c0541b93f29355b940c347e276bb71";
$callback_url = HTTP.USER_URL."API/Kaixin/Callback.php";
$scope="basic"; //权限列表，具体权限请查看官方的api文档
?>