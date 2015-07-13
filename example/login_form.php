<?php
session_start();
if($_POST) {
	$data['request_type'] = 'login';
	$data['data']['username'] = $_POST['username'];
	$data['data']['password'] = $_POST['password'];

	$url = 'http://ids.com/api';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_POST, 1);

	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	$output = curl_exec($ch);
	curl_close($ch);

	$result = json_decode($output, true);
	$username = $_POST['username'];
	$app_secret = 'xxx';
	$validate_token = MD5($username . $app_secret);
	if($result['errCode'] === 0) {
		if($validate_token === $result['data']['token']) {
			$_SESSION['username'] = $result['data']['username'];
			header('Location:http://localhost/ids/example/index.php');
		} else {
			echo 'token invalidate';
		}
	} else {
		echo '用户名与密码不匹配';
	}
}
?>
<form method='post' action=''>
	用户名：<input type='text' name='username'>
	密码：<input type='password' name='password'>
	<input type='submit' value='登录'>
</form>
