<?php namespace App\Http\Controllers\Auth;

use Input, Redirect;
use Auth;
use Crypt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	use AuthenticatesAndRegistersUsers;

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 * @return void
	 */
	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->auth = $auth;
		$this->registrar = $registrar;

		$this->middleware('guest', ['except' => 'getLogout']);
	}

	public function getLogin()
	{
		if(!isset($_GET['app'])) {
			return view('auth.login');
		}
		$app = $_GET['app'];//代号
		$app_name = 'xx';//中文名称
		$app_url = 'http://localhost/ids/example';//地址
		return view('auth.login', ['app_name' => $app_name, 'app_url' => $app_url]);
	}

	public function postLogin(Request $request)
	{
		if($request->has('app_url')) {
			return $this->idsLogin($request);
		}
		$this->validate($request, ['username' => 'required', 'password' => 'required']);
		$credentials = $request->only('username', 'password');
		if (Auth::attempt($credentials, $request->has('remember'))) {
			return redirect()->guest('home');
		} else {
			return redirect()->guest('auth/login')
				->withInput()
				->withErrors('用户名与密码不匹配，请重试！');
		}
	}

	private function idsLogin($request)
	{
		$this->validate($request, ['username' => 'required', 'password' => 'required']);
		$credentials = $request->only('username', 'password');
		if (Auth::attempt($credentials, $request->has('remember'))) {
			$app_secret = 'example_secret';
			$token = MD5($request->username . $app_secret);
			header("Location: http://localhost/ids/example/login.php?username=" . $request->username . "&token=$token");
			exit;
		}
		else{
			return redirect()->guest('auth/login?app=xxx')
				->withInput()
				->withErrors('用户名与密码不匹配，请重试！');
		}
		// exit;
	}

}
