<?php
include_once('config.php');
session_start();
if($_POST) {
	$data['request_type'] = 'get_token';
	$data['data']['app'] = app;
	$data['data']['username'] = $_POST['username'];
	$data['data']['password'] = $_POST['password'];

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, ids_home . '/api');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

	$output = curl_exec($ch);
	curl_close($ch);
	$result = json_decode($output, true);

	if($result['errCode'] !== 0) {
		echo '用户名与密码不匹配';
		return;
	}

	$data['request_type'] = 'login';
	$data['data']['token'] = $result['data']['token'];

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, ids_home . '/api');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

	$output = curl_exec($ch);
	curl_close($ch);
	$result = json_decode($output, true);

	if($result['errCode'] !== 0) {
		echo '登录失败';
	} else {
		$_SESSION['username'] = $result['data']['username'];
		header('Location:' . site_home);
	}
}
?>
<form method='post' action=''>
	用户名：<input type='text' name='username'>
	密码：<input type='password' name='password'>
	<input type='submit' value='登录'>
</form>
