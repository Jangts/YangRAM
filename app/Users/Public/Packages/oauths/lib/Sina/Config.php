<?php
//配置文件
header("Content-Type: text/html; charset=UTF-8");
require $_SERVER["DOCUMENT_ROOT"]."/Tangram/Tangram.php";
require PATH_DRV."Logger/Sina".DLL;
 
$app_key = "2822919582";
$app_secret = "e33b68162e98e62afe795a5e5f735a83";
$callback_url = HTTP.USER_URL."API/Sina/Callback.php";
?>