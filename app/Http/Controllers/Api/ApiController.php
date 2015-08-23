<?php namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Crypt;
use Illuminate\Http\Request;
use App\App;

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
		if(!isset($input['data']['app'])) {
			$data['errCode'] = 10000;
			$data['errMsg'] = 'no app field in post data';
			return json_encode($data);
		}
		$app = $input['data']['app'];
		$app_info = App::where('app', '=', $app)->first();
		if(is_null($app_info)) {
			$data['errCode'] = 10000;
			$data['errMsg'] = 'app invalid';
			return json_encode($data);
		}
		if(Auth::validate($credentials)) {
			$token_array['username'] = $input['data']['username'];
			$token_array['app'] = $app_info['app'];
			$token_array['app_secret'] = $app_info['app_secret'];
			$token_array['timestamp'] = time();
			$token = Crypt::encrypt($token_array);

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
		$token_array = Crypt::decrypt($input['data']['token']);
		if(is_null($token_array)) {
			$data['errCode'] = 10001;
			$data['errMsg'] = 'token invalid';
		 } elseif(time() - $token_array['timestamp'] > 8) {
			 $data['errCode'] = 1000;
			 $data['errMsg'] = 'token expired';
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
