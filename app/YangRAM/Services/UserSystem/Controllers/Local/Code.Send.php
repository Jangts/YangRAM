<?php
include_once $_SERVER['DOCUMENT_ROOT']."/Tangram/Tangram.php";
require_once PATH_CONF."mail.php";

if(isset($_GET["ck"])){
	if($_GET["ck"]==="sendmail"){
		if(isset($_POST["email"])){
			$email = $_POST["email"];
			$userInfo = getUserInfoByMail($email);
			if(isset($userInfo["uid"])){
				$uid = $userInfo["uid"];
				$username = $userInfo["username"];
				$code = md5($email.uniqid(), false);
				$url = HTTP."/user/modpw/$code/";
				$title = "请确认进行帐号密码重置证";
				$con = "<html><body>亲爱的<strong>".$username.", 您好！</strong>,";
				$con .= "<p>您于".date("Y-m-d H:i:s")."申请了找回密码服务，如果不是您申请的，请忽略本邮件，如果是您申尽快前往";
				$con .= '<span style="font-size:24px;"><a href="'.$url.'"> 重置页面</a></span>(可复制该链接)';
				$con .= "修改您的密码，以免个人信息丢失。</p>";
				$con .= "<p><i>(验证码72小时内有效)</i></p>";
				$con .= "<p>如果不能点击上面链接，请复制下面地址到浏览器中手动访问：".$url."</p></body></html>";
				$result = tSmtpMail($email, $title, $con, $username); 
				if($result==="isSend"){
					writeCode($code, $uid, date("Y-m-d H:i:s", strtotime("+3 day")), $email);
				}
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
?>