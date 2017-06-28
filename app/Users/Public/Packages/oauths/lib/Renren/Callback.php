<?php
session_start();
require_once "Config.php";
 
if(isset($_GET["code"])&&$_GET["code"]!=""){
    $logIn = new logInByRR($app_key, $app_secret);
    $result = $logIn->token($callback_url, $_GET["code"]);
}
if(isset($result["access_token"])&&$result["access_token"]!=""){	
	//保存登录信息，此示例中使用session保存
	$_SNS["type"] = "Renren";
	$_SNS["token"]["RR"] = $result["access_token"];
    $_SNS["refresh"]["RR"] = $result["refresh_token"];
	/////////////////////////////
	$_SNS["oid"] = "";
	$_SNS["uid"] = $result["user"]["id"];
	$_SNS["username"] = $result["user"]["name"];
	$_SNS["avatar"] = $result["user"]["avatar"][3]["url"];
	$_SNS["nickname"] = $result["user"]["name"];
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

/**
//access token到期后使用refresh token刷新access token
$result=$logIn->refresh($_SNS["refresh"]);
var_dump($result);
**/
 
/**
//发布微博
$result=$logIn->addBlog('微博标题', '微博内容<br/><img src="http://www.baidu.com/img/baidu_sylogo1.gif">');
var_dump($result);
**/
?>
