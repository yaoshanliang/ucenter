<?php namespace App\Http\Controllers\Api\V1;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Crypt;
use Illuminate\Http\Request;
use App\App;
use App\Services\Api;
use Validator;
use Illuminate\Contracts\Encryption\DecryptException;

class ApiController extends Controller {

	//get方法禁止访问
	public function forbidden() {
		return Api::json_return(403);
	}


	//根据request_type分发
	public function index(Request $request)
	{
		$input = $request->all();
		// $input = json_decode(file_get_contents("php://input"), true);
		if(!isset($input['action'])) {
			return Api::json_return(1000);
		}
		switch($input['action']) {
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
	public function get_token($request) {

		$credentials['username'] = $input['data']['username'];
		$credentials['password'] = $input['data']['password'];
		if(!isset($input['data']['app'])) {
			return Api::json_return(1002);
		}
		$app = $input['data']['app'];
		$app_info = App::where('app', '=', $app)->first();
		if(is_null($app_info)) {
			$data['code'] = 10000;
			$data['msg'] = 'app invalid';
			return json_encode($data);
		}
		if(Auth::validate($credentials)) {
			$token_array['username'] = $input['data']['username'];
			$token_array['app'] = $app_info['app'];
			$token_array['app_secret'] = $app_info['app_secret'];
			$token_array['timestamp'] = time();
			$token = Crypt::encrypt($token_array);

			$data['code'] = 1;
			$data['msg'] = trans("api.$data[code]");
			$data['data']['token'] = $token;
		} else {
			$data['code'] = 111;
			$data['msg'] = 'username and password do not match';
		}
		return json_encode($data);
	}

	//验证token
	private function validate_token($input)
	{
		try {
			$token_array = Crypt::decrypt($input['data']['token']);
		} catch(DecryptException $e) {
			return Api::json_return(1002);
			// var_dump($e);exit;
		}
		if(is_null($token_array)) {
			return Api::json_return(1002);
		} elseif(time() - $token_array['timestamp'] > 8) {
			return Api::json_return(1003);
		} else {
			$user_id = 1000;
			$username = $token_array['username'];
			return Api::json_return(1, '', compact('user_id', 'username'));
		 }
	}

	private function login($input)
	{
		//验证
		if(Validator::make($input['data'], ['token' => 'required'])->fails()) {
			return Api::json_return(1000, $validator->messages()->first());
		}
		$validate_token = $this->validate_token($input);
		$result = json_decode($validate_token, true);
		if($result['code'] !== 1) {
			$data = $result;
		} else {
			$user_id = $result['data']['user_id'];
			$username = $result['data']['username'];
			return Api::json_return(1, '', compact('user_id', 'username'));
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
