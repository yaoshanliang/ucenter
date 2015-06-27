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

	public function get_token() {
		$token =  csrf_token();
		if($token) {
			$data['errCode'] = 0;
			$data['token'] = $token;
		}
		else {
			$data['errCode'] = 10000;
			$data['errMsg'] = 'Failed to get token';
		}
		return json_encode($data);
	}

	//根据request_type分发
	public function index(Request $request)
	{
		$input = json_decode(file_get_contents("php://input"), true);
		var_dump($input);
		if(isset($input['request_type'])) {
			$data['errCode'] = 10001;
			$data['errMsg'] = 'Request_type is missing';
		}
		switch($input['request_type']) {
			case 'login':
				$data = $this->login($input);
				break;
		}
		return json_encode($data);
	}
	public function loginGet()
	{
	    return view('login');
	}

	private function login($input)
	{
	    if (Auth::attempt(['email' => $input['data']['username'], 'password' => $input['data']['password']])) {
			$data['errCode'] = 0;

		} else {
			$data['errCode'] = 10002;
			$errMsg['errMsg'] = 'Username or password is incorrect';
		}
		return $data;

	}

	public function logout()
	{
	    if (Auth::check()) {
		        Auth::logout();
	    }
	    return Redirect::route('login');
	}

}
