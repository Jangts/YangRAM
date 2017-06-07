<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/Tangram/Tangram.php";
include_once "Functions.php";

if(isset($_GET["ck"])){
	if($_GET["ck"]==="match"){
		if(isset($_GET["code"])&&isset($_GET["email"])){
			$code = $_GET["code"];
			$email = $_GET["email"];
			$userInfo = getUserInfoByMail($email);
			if(isset($userInfo["uid"])){
				$uid = $userInfo["uid"];
				matchCode($code, $uid); 
			}else{
				die("TAM-ERROR-0404");
			}
		}else{
			die("TAM-ERROR-0111");
		}
	}else{
		die("TAM-ERROR-0110");
	}
}else{
	die("TAM-ERROR-0010");
}