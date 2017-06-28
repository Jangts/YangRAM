<?php
//配置文件
header("Content-Type: text/html; charset=UTF-8");
require $_SERVER["DOCUMENT_ROOT"]."/Tangram/Tangram.php";
require PATH_DRV."Logger/360".DLL;
 
$app_key = "64934c6228800f2fdbc2a179d13ba4b9";
$app_secret = "c2863d6891b84722429f45f52ea08ad5";
$callback_url = HTTP.USER_URL."API/360/Callback.php";
?>