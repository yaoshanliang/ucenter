<?php
include_once('config.php');
session_start();
if(isset($_SESSION['username'])) {
	header('Location:' . site_home);
	exit;
} else {
	$validate_token = MD5($_GET['username'] . app_secret);
	if($validate_token === $_GET['token']) {
		$_SESSION['username'] = $_GET['username'];
		header('Location:' . site_home);
	} else {
		// echo 'token 不合法！';//上线时不显示
		echo '登录失败';
	}
}
?>
