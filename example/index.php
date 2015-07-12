<?php
session_start();
if($_SESSION['username']) {
	//自行登录成功后的处理
	echo $_SESSION['username'], ' login';
} else {
	header('Location : http://ids.com/auth/login?app=example');//app名称需要申请
}
?>
