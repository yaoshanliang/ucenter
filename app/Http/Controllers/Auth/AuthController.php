<?php namespace App\Http\Controllers\Auth;

use Input, Redirect;
use Auth;
use Crypt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use App\App;
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
		$app = $_GET['app'];
		$app_info = App::where('app', '=', $app)->first();
		if($app != '' && is_null($app_info)) {
			return view('auth.forbidden');
		}
		return view('auth.login', ['app_info' => $app_info]);
	}

	public function postLogin(Request $request)
	{
		if($request->has('app')) {
			return $this->idsLogin($request);
		}
		$this->validate($request, ['username' => 'required', 'password' => 'required']);
		$request->email = $request->username;
		// var_dump($request);exit;
		// $credentials = $request->only('username', 'password');
		$credentials = array('username' => $request->username, 'password' => $request->password);
		$credentials = array('email' => $request->username, 'password' => $request->password);
		$credentials = array('phone' => $request->username, 'password' => $request->password);
		if (Auth::attempt($credentials, $request->has('remember'))) {
			if (!Auth::user()->is_admin) {
				return redirect()->guest('/home');
		    } else {
				return redirect()->guest('/admin');
			}
			return redirect()->guest('home');
		} else {
			return redirect()->guest('auth/login')
				->withInput()
				->withErrors('账户与密码不匹配，请重试！');
		}
	}

	private function idsLogin($request)
	{
		$this->validate($request, ['username' => 'required', 'password' => 'required']);
		$credentials = $request->only('username', 'password');
		if(Auth::validate($credentials)) {
			$app = $request->app;
			$app_info = App::where('app', '=', $app)->first();

			$token_array['username'] = $request->username;
			$token_array['app'] = $app_info['app'];
			$token_array['app_secret'] = $app_info['app_secret'];
			$token_array['timestamp'] = time();
			$token = Crypt::encrypt($token_array);
			header('Location:' . $app_info['app_login_url'] . '?token=' . $token);
			exit;
		}
		else{
			return redirect()->guest('auth/login?app=' . $request->app)
				->withInput()
				->withErrors('用户名与密码不匹配，请重试！');
		}
	}

}
