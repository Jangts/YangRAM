<?php
function accountReg($un, $em, $pw){
	$time = date("Y-m-d H:i:s");
	if(isset($_POST["avatar"])&&$avatar!=USER_AVATAR){
		$avatar = $_POST["avatar"];
	}else{
		$avatar = USER_AVATAR;
		
	}
	$tq = new tQuery;
	$tq->table = DB_USE."account";
	$tq->inserts = array(
		"username" => $un,
		"password" => $pw,
		"avatar" => $avatar,
		"email" => $em,
		"regtime" => $time,
		"lasttime" => $time,
	);
	$tq->insert();
	$result = $tq->index;
	unset($tq);
	if($result>0){
		$GLOBALS["uid"] = $result;
		return true;
	}else{
		return false;
	}
}

function snsReg(){
	global $uid;
	if(isset($_SESSION["SNS"])){
		$_SNS = $_SESSION["SNS"];
		$tq = new tQuery;
		$tq->table = DB_USE."sns";
		switch($_SNS["type"]){
			case "QQ":
			$tq->inserts = array(
				"uid" => $uid,
				"oid_qq" => $_SNS["oid"],
			);
			break;
			case "Sina":
			$tq->inserts = array(
				"uid" => $uid,
				"uid_sina" => $_SNS["uid"],
			);
			break;
			case "360":
			$tq->inserts = array(
				"uid" => $uid,
				"uid_360" => $_SNS["uid"],
			);
			break;
			case "Baidu":
			$tq->inserts = array(
				"uid" => $uid,
				"uid_baidu" => $_SNS["uid"],
			);
			break;
			case "Douban":
			$tq->inserts = array(
				"uid" => $uid,
				"uid_douban" => $_SNS["uid"],
			);
			break;
			case "Kaixin":
			$tq->inserts = array(
				"uid" => $uid,
				"uid_kaixin" => $_SNS["uid"],
			);
			break;
			case "Renren":
			$tq->inserts = array(
				"uid" => $uid,
				"uid_renren" => $_SNS["uid"],
			);
			break;
			case "Normal":
			default:
			$tq->inserts = array(
				"uid" => $uid,
			);
			break;
		}
		$tq->insert();
		unset($tq);
		unset($_SESSION["SNS"]);
	}
}

function matchCode($code, $uid){
	$sql = "SELECT * FROM tam_user_code WHERE verify_code = '$code'";
	$sql .= " AND USER_ID = '$uid' ORDER BY id DESC;";
	$result = tQuery($sql);
	$row = mysqli_fetch_array($result);
	if(isset($row["dateline"])){
		if($row["dateline"]>=date("Y-m-d H:i:s")){
			$userInfo = getUserInfoById($uid);
			$_SESSION["safeuser"] = $userInfo["username"];
			$_SESSION["username"] = $userInfo["username"];
			$_SESSION["password"] = $userInfo["password"];
			setcookie("username", $_SESSION["username"], time()+1200, "/");
			delCode($row["id"]);
			echo $row["verify_code"];
		}else{
			die("TAM-ERROR-7304");
		}
	}
}

function delCode($id){
	$sql = "DELETE FROM tam_user_code WHERE id = '$id'";
	$result = tQuery($sql);
}
function snsLink($uid){
	if(isset($_SESSION["SNS"])&&$_SESSION["SNS"]["type"]!="Normal"){
		$_SNS = $_SESSION["SNS"];
		$table = DB_USE."sns";
		$require = "uid = $uid";
		switch($_SNS["type"]){
			case "QQ":
			$update = "oid_qq = '".$_SNS["oid"]."'";
			break;
			case "Sina":
			$update = "uid_sina = ".$_SNS["uid"];
			break;
			case "360":
			$update = "uid_360 = ".$_SNS["uid"];
			break;
			case "Baidu":
			$update = "uid_baidu = ".$_SNS["uid"];
			break;
			case "Douban":
			$update = "uid_douban = ".$_SNS["uid"];
			break;
			case "Kaixin":
			$update = "uid_kaixin = ".$_SNS["uid"];
			break;
			case "Renren":
			$update = "uid_renren = ".$_SNS["uid"];
			break;
		}
		upData($table, $require, $update);
		unset($_SESSION["SNS"]);
	}
}
