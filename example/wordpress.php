<?php
require_once('wp-load.php');
define('app', 'wordpress');//应用名称，需要申请
define('app_secret', 'example_secret');//应用密钥，需要申请
define('site_home', 'http://wordpress.iat.net.cn');//本站地址
define('ucenter_home', 'http://ucenter.iat.net.cn');//用户中心地址
define('ucenter_login_url', ucenter_home . '/auth/login');//用户中心登录地址

if(isset($_GET['token'])) {
    $data['action'] = 'login';
    $data['data']['token'] = htmlentities($_GET['token']);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, ucenter_home . '/api');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $output = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($output, true);

    if($result['code'] !== 1) {
        wp_die('登录失败');//具体原因见 $result['msg'];
    } else {
		$user_info = $result['data'];

		$current_user = get_user_by('login', $user_info['username']);
		if(is_wp_error($current_user) || !$current_user) {
			$random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
			$user_id = wp_insert_user(array(
				'user_login' => $user_info['username'],
				'display_name' => $user_info['username'],
				'nick_name' => $user_info['username'],
				'user_pass' => $random_password
			));
			wp_set_auth_cookie($user_id);
		} else {
			wp_set_auth_cookie($current_user->ID);
		}
		header('Location: ' . home_url() . '/wp-admin');
		exit;
    }
}
if(!isset($_GET['action'])) {
	header('Location:' . ucenter_login_url . '?app=' . app);
	exit;
}
