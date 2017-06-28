<?php
session_start();
require_once "Config.php";

$logIn = new logInByQh($app_key, $app_secret);
$logUrl = $logIn->logUrl($callback_url);
echo '<script>window.location.href="'.$logUrl.'";</script>';
?>
