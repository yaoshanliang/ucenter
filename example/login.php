<?php
include_once('config.php');
session_start();
if(isset($_SESSION['username'])) {
	header('Location:' . site_home);
	exit;
} else {
	$data['request_type'] = 'login';
	$data['data']['token'] = $_GET['token'];

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
