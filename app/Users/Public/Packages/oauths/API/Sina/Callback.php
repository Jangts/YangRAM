<?php
session_start();
require_once "Config.php";

function getimgp($u){
    //图片处理
    $c=@file_get_contents($u);
    $name=md5($u).'.jpg';
    $mime='image/unknown';
    return array($mime, $name, $c);
}
 
if(isset($_GET["code"])&&$_GET["code"]!=""){
    $logIn = new logInBySina($app_key, $app_secret);
    $result = $logIn->token($callback_url, $_GET["code"]);
}
if(isset($result["access_token"])&&$result["access_token"]!=""){
	$logIn = new logInBySina($app_key, $app_secret, $result["access_token"]);
	$_info = $logIn->getUid();
	$uid = $_info["uid"];
	$info = $logIn->showUserById($uid);
	
	//保存登录信息，此示例中使用session保存
	$_SNS["type"] = "Sina";
	$_SNS["token"]["WB"] = $result["access_token"];
	/////////////////////////////
	$_SNS["oid"] = "";
	$_SNS["uid"] = $result["uid"];
	$_SNS["username"] = $info["domain"];
	$_SNS["avatar"] = $info["avatar_hd"];
	$_SNS["nickname"] = $info["name"];
	/////////////////////////////
	$_SNS["birthday"] = "";
	$_SNS["year"] = "";
	$_SNS["gender"] = $info["gender"];
	$_SNS["hometown"] = "";
	$_SNS["province"] = $info["province"];
	$_SNS["city"] = $info["city"];
	$_SNS["location"] = $info["location"];
	$_SNS["desc"] = $info["description"];
	//专门信息////////////////////
	$_SESSION["weibo"] = $info["url"];
}else{
    die("TAM-ERROR-7007");
}
$isLogIn = isSNSLogIn($_SNS["type"], $_SNS["uid"]);
LogInBySNSCheck($isLogIn[0], $isLogIn[1]);

/**
//发布微博
$content='微博内容';
$img='http://www.baidu.com/img/baidu_sylogo1.gif';
$img_a=getimgp($img);
if($img_a[2]!=''){
	$result=$logIn->update($content, $img_a);
//发布带图片微博
}else{
	$result=$logIn->update($content);
//发布纯文字微博
}
var_dump($result);
**/
 
/**
//微博列表
$result=$logIn->user_timeline($uid);
var_dump($result);
**/
?>
