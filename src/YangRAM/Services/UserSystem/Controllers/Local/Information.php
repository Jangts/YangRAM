<?php
include_once "Functions.php";
isset($_GET["aim"]) or die("TAM-ERROR-0010");
switch($_GET["aim"]){
	case "info_edit":
	$userinfo = UserInfo::$userinfo;
	$personal = UserInfo::$personal;
	$sns = UserInfo::$sns;
	$tq = new tQuery;
	if(count($updates = array_intersect_key($_POST, $userinfo))>0){
		$tq->table = DB_USE."account";
		$tq->updates = $updates;
		$tq->require = array("uid"=>$__USER["uid"]);
		$tq->update();
	}
	if(count($updates = array_intersect_key($_POST, $personal))>0){
		$tq->table = DB_USE."personal";
		$tq->updates = $updates;
		$tq->require = array("uid"=>$__USER["uid"]);
		$tq->update();
	}
	if(count($updates = array_intersect_key($_POST, $sns))>0){
		$tq->table = DB_USE."sns";
		$tq->updates = $updates;
		$tq->require = array("uid"=>$__USER["uid"]);
		$tq->update();
	}
	unset($tq);
	echo "succeed";
	break;
	case "pw_set":
	isset($_POST["username"])&&isset($_POST["password"]) or die("TAM-ERROR-0113");
	$username = $_POST["username"];
	$password = md5($_POST["password"], false);
	$tq = new tQuery;
	$tq->table = DB_USE."account";
	$tq->updates = array("password"=>$password);
	$tq->require = array("username"=>$username);
	$result = $tq->update();
	if($result){
		unset($_SESSION["username"]);
		unset($_SESSION["password"]);
		setcookie("username", "", time()-1, "/");
		echo "succeed";
	}
	break;
	case "pw_mod":
	isset($_POST["password"])&&isset($_POST["current_password"])&&isset($_SESSION["username"]) or die("TAM-ERROR-0113");
	$password = md5($_POST["password"], false);
	$current = md5($_POST["current_password"], false);
	$tq = new tQuery;
	$tq->table = DB_USE."account";
	$tq->updates = array("password"=>$password);
	$tq->require = array("username"=>$_SESSION["username"], "password"=>$current);
	$result = $tq->update();
	if($result){
		unset($_SESSION["username"]);
		unset($_SESSION["password"]);
		setcookie("username", "", time()-1, "/");
		echo "succeed";
	}
	break;
}