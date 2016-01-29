<?php

namespace App\Http\Controllers\Auth;

use App\Model\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;
use Cache;
use Session;
use Queue;
use App\Jobs\UserLog;

class AuthController extends Controller
{
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

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
		return Validator::make($data, [
			'username' => 'required|max:255|unique:users|min:5',
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|confirmed|min:6',
		]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
		return User::create([
			'username' => $data['username'],
			'email' => $data['email'],
			'password' => bcrypt($data['password']),
		]);
    }

	public function getLogin()
	{
		if(!isset($_GET['app'])) {
			return view('auth.login');
		}
		$app = $_GET['app'];
		// $app_info = Cache::get($settings_prefix . $app, function() use ($app, $settings_prefix) {
			// $setting = Setting::where('name', $app)->first(array('value'));
			// Cache::forever($settings_prefix . $app, $setting['value']);
			// return $setting['value'];
		// });
		$app_info = App::where('name', '=', $app)->first();
		if($app != '' && is_null($app_info)) {
			return view('auth.forbidden');
		}
		return view('auth.login', ['app_info' => $app_info]);
	}

	public function postLogin(Request $request, Response $response)
	{
		if($request->has('app')) {
			return $this->idsLogin($request);
		}
		$this->validate($request, ['username' => 'required', 'password' => 'required']);

		$username = $request->username;
		$password = $request->password;
		$credentials = array();
		if(strpos($username, '@') !== false) {
			$credentials = array('email' => $username, 'password' => $password);
		} elseif(preg_match('/^\d{11}$/', $username)) {
			$credentials = array('phone' => $username, 'password' => $password);
		}
		if(!empty($credentials) && Auth::attempt($credentials, $request->has('remember'))) {
			$this->initRole($request, $response);
			$this->loginLog($request, $credentials);
                return redirect()->intended();
            dd( $request->headers->get('referer'));
            // dd(Redirect::back());
            dd(URL::previous());exit;
            dd( Request::header('referer'));
			return redirect('/admin');
		}
		$credentials = array('username' => $request->username, 'password' => $request->password);
		if (Auth::attempt($credentials, $request->has('remember'))) {
			$this->initRole($request, $response);
			$this->loginLog($request, $credentials);
            return Redirect::back();
			return redirect()->guest('/admin');
		} else {
			return redirect()->guest('/auth/login')
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
			$app_info = App::where('name', '=', $app)->first();

			$token_array['username'] = $request->username;
			$token_array['app'] = $app_info['app'];
			$token_array['app_secret'] = $app_info['app_secret'];
			$token_array['timestamp'] = time();
			$token = Crypt::encrypt($token_array);
			header('Location:' . $app_info['login_url'] . '?token=' . $token);
			exit;
		}
		else{
			return redirect()->guest('auth/login?app=' . $request->app)
				->withInput()
				->withErrors('用户名与密码不匹配，请重试！');
		}
	}

	//初始化角色、应用、当前角色、当前应用
	private function initRole($request, $response)
	{
		$roles_array = $request->user()->roles;
		foreach($roles_array as $v) {
			$apps[$v->app_id] = Cache::get('apps:' . $v->app_id, function() { $this->cacheApps(); });
			$roles[$v->app_id][$v->id] = Cache::get('roles:' . $v->id, function() { $this->cacheRoles(); });
		}
		$current_app = Session::get('current_app', function() use ($apps) {
			$first_app = reset($apps);
			Session::put('current_app', $first_app);
			Session::put('current_app_title', $first_app['title']);
			Session::put('current_app_id', $first_app['id']);
			return $first_app;
		});
		$current_role = Session::get('current_role', function() use ($roles, $current_app) {
			$first_role = $roles[$current_app['id']];
			Session::put('current_role', $first_role);
			Session::put('current_role_title', $first_role[$current_app['id']]['title']);
			Session::put('current_role_id', $first_role[$current_app['id']]['id']);
			return $first_role;
		});

		Session::put('apps', $apps);
		Session::put('roles', $roles);
	}

	//登录日志
	private function loginLog($request, $credentials) {
		$login_way = key($credentials) . ' : ' . current($credentials);
		$ips = $request->ips();
		$ip = $ips[0];
		$ips = implode(',', $ips);
		$log = Queue::push(new UserLog(1, Auth::user()->id, 'S', '登录', $login_way, '', $ip, $ips));
	}
}
