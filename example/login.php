<?php
session_start();
if(isset($_SESSION['username'])) {
	header('Location : http://localhost/ids/example/index.php');
} else {
	$username = $_GET['username'];
	$token = $_GET['token'];
	$app_secret = 'xxx';
	$validate_token = MD5($username . $app_secret);
	if($validate_token === $token) {
		$_SESSION['username'] = $username;
		header('Location:http://localhost/ids/example/index.php');
	} else {
		echo 'token invalidate';
	}
	/*
	$url = 'http://ids.com/api';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	$data['username'] = 'iat';

	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	$output = curl_exec($ch);
	curl_close($ch);
	//打印获得的数据
	print_r($output);
	exit;
	$token = file_get_contents('http://localhost/ids/public/api/validate_token');
	if($token === $_GET['token']) {
		$_SESSION['username'] = 122;
	header('Location:http://localhost/ids/example/index.php');
	} else {
		echo 'token invalidate';
	}
	var_dump($_GET['token']);
	 */
}
?>
