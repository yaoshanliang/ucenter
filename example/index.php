<?php
session_start();
if(isset($_SESSION['username'])) {
	//自行登录成功后的处理
	echo $_SESSION['username'], ' login', '<br />';
	echo '<a href="http://localhost/ids/example/logout.php">退出</a>';
} else {
	echo '<a href="http://ids.com/auth/login?app=example">登录</a>';
	// header('Location:http://ids.com/auth/login?app=example');//app名称需要申请
}
?>
