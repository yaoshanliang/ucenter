<?php namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
// use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller {

	//get方法禁止访问
	public function forbidden() {
		$data['errCode'] = 403;
		$data['errMsg'] = 'Forbidden visting';
		return json_encode($data);
	}


	//根据request_type分发
	public function index(Request $request)
	{
		$input = json_decode(file_get_contents("php://input"), true);
		if(!isset($input['request_type'])) {
			$data['errCode'] = 10001;
			$data['errMsg'] = 'Request_type is missing';
			return json_encode($data);
		}
		switch($input['request_type']) {
			case 'get_token':
				$data = $this->get_token($input);
				break;
			case 'login':
				$data = $this->login($input);
				break;
		}
		return $data;
	}
	public function loginGet()
	{
	    return view('login');
	}

	//获取token
	public function get_token($input) {

		$credentials['username'] = $input['data']['username'];
		$credentials['password'] = $input['data']['password'];
		if(Auth::validate($credentials)) {
			$app_secret = 'example_secret';
			$token_array['username'] = $input['data']['username'];
			$token_array['app'] = 'example';
			$token_array['app_secret'] = $app_secret;
			$token_array['timestamp'] = time();
			$token = base64_encode(json_encode($token_array));
			$data['errCode'] = 0;
			$data['data']['token'] = $token;
		} else {
			$data['errCode'] = 111;
			$data['errMsg'] = 'username and password do not match';
		}
		return json_encode($data);
	}

	//验证token
	private function validate_token($input)
	{
		$token_array = json_decode(base64_decode($input['data']['token']), true);
		if(is_null($token_array) || $token_array['app_secret'] != 'example_secret') {
			$data['errCode'] = 10001;
			$data['errMsg'] = 'token invalid';
		 } else {
			 $data['errCode'] = 0;
			 $data['data']['username'] = $token_array['username'];
		 }
		return json_encode($data);

	}

	private function login($input)
	{
		$validate_token = $this->validate_token($input);
		$result = json_decode($validate_token, true);
		if($result['errCode'] !== 0) {
			$data = $result;
		} else {
			$data['errCode'] = 0;
			$data['data']['username'] = $result['data']['username'];
		}
		return json_encode($data);
	}

	public function logout()
	{
	    if (Auth::check()) {
		        Auth::logout();
	    }
	    return Redirect::route('login');
	}
}
