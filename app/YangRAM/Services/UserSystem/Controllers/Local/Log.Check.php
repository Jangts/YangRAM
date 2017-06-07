<?php
include_once "Functions.php";
isset($_GET["aim"]) or die("TAM-ERROR-0010");
switch($_GET["aim"]){
	case "login":
	if(isset($_POST["username"])&&isset($_POST["password"])){
		$username = getLowerStr($_POST["username"]);
		$password = md5($_POST["password"], false);
		$sql = "SELECT * FROM ".DB_USE."account";
		$sql .= " WHERE username = '$username'";
		$sql .= " AND password = '$password'";
		$result = tQuery($sql);
		$row = mysqli_fetch_array($result);
		if(isset($row["username"])){
			$_SESSION["username"] = $row["username"];
			$_SESSION["password"] = $_POST["password"];
			setcookie("username", $_SESSION["username"], time()+60*60*24*7, "/");
			snsLink($row["uid"]);
			echo $row["username"];
		}
	}elseif(isset($_POST["uid"])&&isset($_POST["password"])){
		$uid = $_POST["uid"];
		$password = md5($_POST["password"], false);
		$sql = "SELECT * FROM ".DB_USE."account";
		$sql .= " WHERE uid = '$uid'";
		$sql .= " AND password = '$password'";
		$result = tQuery($sql);
		$row = mysqli_fetch_array($result);
		if(isset($row["username"])){
			$_SESSION["username"] = $row["username"];
			$_SESSION["password"] = $_POST["password"];
			setcookie("username", $_SESSION["username"], time()+60*60*24*7,"/");
			echo $row["uid"];
		}
	}elseif(isset($_POST["email"])&&isset($_POST["password"])){
		$email = getLowerStr($_POST["email"]);
		$password = md5($_POST["password"], false);
		$sql = "SELECT * FROM ".DB_USE."account";
		$sql .= " WHERE email = '$email'";
		$sql .= " AND password = '$password'";
		$result = tQuery($sql);
		$row = mysqli_fetch_array($result);
		if(isset($row["username"])){
			$_SESSION["username"] = $row["username"];
			$_SESSION["password"] = $_POST["password"];
			setcookie("username", $_SESSION["username"], time()+60*60*24*7, "/");
			echo $row["email"];
		}
	}elseif(isset($_POST["safeuser"])&&isset($_POST["password"])){
		$safeuser = getLowerStr($_POST["safeuser"]);
		$password = md5($_POST["password"], false);
		$sql = "SELECT * FROM ".DB_USE."account";
		$sql .= " WHERE password = '$password'";
		$sql .= " AND ( username = '$safeuser'";
		$sql .= " OR email = '$safeuser'";
		$sql .= " OR uid = '$safeuser' )";
		$result = tQuery($sql);
		$row = mysqli_fetch_array($result);
		if(isset($row["username"])){
			$_SESSION["safeuser"] = $row["username"];
			$_SESSION["username"] = $row["username"];
			$_SESSION["password"] = $_POST["password"];
			setcookie("username", $_SESSION["username"], time()+60*60*24*7, "/");
			echo $safeuser;
		}
	}elseif(isset($_POST["adminame"])&&isset($_POST["password"])){
		$username = getLowerStr($_POST["adminame"]);
		$password = md5($_POST["password"], false);
		$sql = "SELECT * FROM ".DB_USE."account";
		$sql .= " WHERE username = '$username'";
		$sql .= " AND password = '$password'";
		$sql .= " AND usergroup = 10";
		$result = tQuery($sql);
		$row = mysqli_fetch_array($result);
		if(isset($row["username"])){
			$_SESSION["username"] = $_SESSION["adminname"] = $row["username"];
			$_SESSION["password"] = $_POST["password"];
			setcookie("adminname", $_SESSION["username"], time()+60*60*12, "/");
			setcookie("username", $_SESSION["username"], time()+60*60*24*7, "/");
			echo $row["username"];
		}
	}else{
		die("TAM-ERROR-0111");
	}
	break;
	case "logout":
	if(isset($_POST["word"])){
		if(isset($_SESSION["adminname"])) unset($_SESSION["adminname"]);
		unset($_SESSION["username"]);
		unset($_SESSION["password"]);
		echo "see-you";
	}else if(isset($_GET["rtn"])){
		if(isset($_SESSION["adminname"])) unset($_SESSION["adminname"]);
		unset($_SESSION["username"]);
		unset($_SESSION["password"]);
		setcookie("username", "", time()-1, "/");
		echo '<script>window.location.href = "'.$_GET["rtn"].'" </script>';
	}else{
		die("TAM-ERROR-0112");
	}
}