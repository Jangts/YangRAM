<?php
session_start();
require_once "Config.php";

if(isset($_GET["code"])&&$_GET["code"]!=""){
    $logIn = new logInByDb($app_key, $app_secret);
    $result = $logIn->token($callback_url, $_GET["code"]);
}
if(isset($result["access_token"])&&$result["access_token"]!=""){
	$logIn = new logInByDb($app_key, $app_secret, $result["access_token"]);
	$info = $logIn->getInfo();
	
	//保存登录信息，此示例中使用session保存
	$_SNS["type"] = "Douban";
	$_SNS["token"]["DB"] = $result["access_token"];
    $_SNS["refresh"]["DB"] = $result["refresh_token"];
	/////////////////////////////
	$_SNS["oid"] = "";
	$_SNS["uid"] = $result["douban_user_id"];
	$_SNS["username"] = "db_".$result["douban_user_id"];
	$_SNS["avatar"] = $info["large_avatar"];
	$_SNS["nickname"] = $info["name"];
	/////////////////////////////
	$_SNS["birthday"] = "";
	$_SNS["year"] = "";
	$_SNS["gender"] = "";
	$_SNS["hometown"] = "";
	$_SNS["province"] = "";
	$_SNS["city"] = "";
	$_SNS["location"] = "";
	$_SNS["desc"] = $info["desc"];
	//专门信息////////////////////
	$_SESSION["douban"] = $info["alt"];
}else{
    die("TAM-ERROR-7007");
}
$isLogIn = isSNSLogIn($_SNS["type"], $_SNS["uid"]);
LogInBySNSCheck($isLogIn[0], $isLogIn[1]);

/**
//access token到期后使用refresh token刷新access token
$result=$logIn->refresh($callback_url, $_SNS["refresh"]);
var_dump($result);
**/

/**
//发布分享
$text='分享内容';
$title='分享标题';
$url='';
$result=$logIn->share($text, $title, $url);
var_dump($result);
**/
?>