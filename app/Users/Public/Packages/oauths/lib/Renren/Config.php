<?php
//配置文件
header("Content-Type: text/html; charset=UTF-8");
require $_SERVER["DOCUMENT_ROOT"]."/Tangram/Tangram.php";
require PATH_DRV."Logger/Renren".DLL;
 
$app_key = "f3a04b810634497cbbae61e166ef648a";
$app_secret = "9a213928c2d04ef8831e8c07879ea354";
$callback_url = HTTP.USER_URL."API/Renren/Callback.php";
$scope='publish_blog read_user_blog'; //权限列表，具体权限请查看官方的api文档
?>