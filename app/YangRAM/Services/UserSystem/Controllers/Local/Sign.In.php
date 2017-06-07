<?php
include_once "Functions.php";

if(isset($_GET["aim"])){
	if($_GET["aim"]==="reg"){
		if(isset($_POST["username"])&&isset($_POST["password"])&&isset($_POST["email"])){
			$username = $_POST["username"];
			$email = getLowerStr($_POST["email"]);
			$password = md5($_POST["password"], false);
			if(!isAccountValid("username",$username)){
				die("TAM-ERROR-0120");
			} elseif(!isAccountValid("email",$email)){
				die("TAM-ERROR-0121");
			}else{
				$result = accountReg($username, $email, $password);
				if($result){
					$_SESSION["username"] = $username;
					$_SESSION["password"] = $password;
					setcookie("username", $username, time()+60*60*24*7);
					snsReg($result);
					echo $username;
				}
			}
		}else{
			die("TAM-ERROR-0113");
		}
	}else{
		die("TAM-ERROR-0110");
	}
}else{
	die("TAM-ERROR-0010");
}
?>