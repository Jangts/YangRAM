<?php
session_start();
require_once "Config.php";
 
if(isset($_GET["code"])&&$_GET["code"]!=""){
    $logIn = new logInByQQ($app_key, $app_secret);
    $result = $logIn->token($callback_url, $_GET["code"]);
}
if(isset($result["access_token"])&&$result["access_token"]!=""){
	$logIn = new logInByQQ($app_key, $app_secret, $result["access_token"]);
	$_info = $logIn->getOpenid();
	$openid = $_info["openid"];
	$info = $logIn->getUserInfoByOid($openid);
	
	//保存登录信息，此示例中使用session保存
	$_SNS["type"] = "QQ";
	$_SNS["token"]["QQ"] = $result["access_token"];
    $_SNS["refresh"]["QQ"] = $result["refresh_token"];
	/////////////////////////////
	$_SNS["oid"] = $openid;
	$_SNS["uid"] = "";
	$_SNS["username"] = $info["nickname"];
	$_SNS["avatar"] = $info["figureurl_qq_2"];
	$_SNS["nickname"] = $info["nickname"];
	/////////////////////////////
	$_SNS["birthday"] = "";
	$_SNS["year"] = $info["year"];
	$_SNS["gender"] = $info["gender"];
	$_SNS["hometown"] = "";
	$_SNS["province"] = $info["province"];
	$_SNS["city"] = $info["city"];
	$_SNS["location"] = "";
	$_SNS["desc"] = "";
}else{
    die("TAM-ERROR-7007");
}
$isLogIn = isSNSLogIn($_SNS["type"], $_SNS["oid"]);
LogInBySNSCheck($isLogIn[0], $isLogIn[1]);

/**
//发布分享
$title=""; //分享页面标题
$url=""; //分享页面网址
$site=""; //QQ应用名称
$fromurl="";  //QQ应用网址
$result=$logIn->share($openid, $title, $url, $site, $fromurl);
var_dump($result);
**/
?>