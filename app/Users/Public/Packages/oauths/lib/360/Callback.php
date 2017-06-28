<?php
session_start();
require_once "Config.php";
 
if(isset($_GET["code"])&&$_GET["code"]!=""){
	$logIn = new logInByQh($app_key, $app_secret);
    $result = $logIn->token($callback_url, $_GET["code"]);
}
if(isset($result["access_token"]) && $result["access_token"]!=""){
	$logIn = new logInByQh($app_key, $app_secret, $result["access_token"]);
	$info = $logIn->getInfo();
	
    //保存登录信息，此示例中使用session保存
	$_SNS["type"] = "360";
	$_SNS["token"]["QH"] = $result["access_token"];
    $_SNS["refresh"]["QH"] = $result["refresh_token"];
	/////////////////////////////
	$_SNS["oid"] = "";
	$_SNS["uid"] = $info["id"];
	$_SNS["username"] = $info["name"];
	$_SNS["avatar"] = $info["avatar"];
	$_SNS["nickname"] = $info["name"];
	/////////////////////////////
	$_SNS["birthday"] = "";
	$_SNS["year"] = "";
	$_SNS["gender"] = "";
	$_SNS["hometown"] = "";
	$_SNS["province"] = "";
	$_SNS["city"] = "";
	$_SNS["location"] = "";
	$_SNS["desc"] = "";
}else{
    die("TAM-ERROR-7007");
}
$isLogIn = isSNSLogIn($_SNS["type"], $_SNS["uid"]);
LogInBySNSCheck($isLogIn[0], $isLogIn[1]);
?>