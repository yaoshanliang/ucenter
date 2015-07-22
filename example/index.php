<?php
include_once('config.php');
session_start();
echo '<b>示例应用首页</b>', '<br />';
if(isset($_SESSION['username'])) {
	//自行登录成功后的处理
	echo $_SESSION['username'], ' login', '<br />';
	echo '<a href=' . site_home . '/logout.php>退出</a>';
} else {
	echo '<a href=' . ids_home . '/auth/login?app=' . app . '>统一身份认证界面登录</a>', '<br />';
	echo '<a href=' . site_home . '/login_form.php>自定义界面登录</a>';
}
?>
