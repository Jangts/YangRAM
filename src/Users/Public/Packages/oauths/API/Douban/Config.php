<?php
//配置文件
header("Content-Type: text/html; charset=UTF-8");
require $_SERVER["DOCUMENT_ROOT"]."/Tangram/Tangram.php";
require PATH_DRV."Logger/Douban".DLL;
 
$app_key = "0cf0cb2f7f15163826d2618e7d3f42a8";
$app_secret = "f353ce69cec893fe";
$callback_url = HTTP.USER_URL."API/Douban/Callback.php";
$scope="shuo_basic_r,shuo_basic_w,douban_basic_common"; //权限列表，具体权限请查看官方的api文档
?>