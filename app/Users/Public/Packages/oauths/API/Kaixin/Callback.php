<?php
session_start();
require_once "Config.php";
 
if(isset($_GET["code"])&&$_GET["code"]!=""){
    $logIn = new logInByKx($app_key, $app_secret);
    $result = $logIn->token($callback_url, $_GET["code"]);
}
if(isset($result["access_token"])&&$result["access_token"]!=""){
	$logIn = new logInByKx($app_key, $app_secret, $result["access_token"]);
	$info = $logIn->getInfo();
	
	//保存登录信息，此示例中使用session保存
	$_SNS["type"] = "Kaixin";
	$_SNS["token"]["KX"] = $result["access_token"];
    $_SNS["refresh"]["KX"] = $result["refresh_token"];
	/////////////////////////////
	$_SNS["oid"] = "";
	$_SNS["uid"] = $info["uid"];
	$_SNS["username"] = $info["name"];
	$_SNS["avatar"] = $info["logo120"];
	$_SNS["nickname"] = $info["name"];
	/////////////////////////////
	$_SNS["birthday"] = $info["birthday"];
	$_SNS["year"] = "";
	$_SNS["gender"] = $info["gender"];
	$_SNS["hometown"] = $info["hometown"];
	$_SNS["province"] = "";
	$_SNS["city"] = $info["city"];
	$_SNS["location"] = "";
	$_SNS["desc"] = "";
}else{
    die("TAM-ERROR-7007");
}
$isLogIn = isSNSLogIn($_SNS["type"], $_SNS["uid"]);
LogInBySNSCheck($isLogIn[0], $isLogIn[1]);
?>
