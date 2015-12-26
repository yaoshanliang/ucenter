<?php namespace App\Http\Controllers\Api;

use Input, Redirect;
use Auth;
use Crypt;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use Session;
use Cache;
use Queue;
use App\App;
use App\Commands\UserLog;
use App\Services\Api;
class Oauth2Controller extends Controller {

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
	}

	public function getLogin(Request $request)
	{
		var_dump($request);exit;
		if(!isset($_GET['app'])) {
			return view('api.auth.forbidden');
		}
		$app = $_GET['app'];
		// $app_info = Cache::get($settings_prefix . $app, function() use ($app, $settings_prefix) {
			// $setting = Setting::where('name', $app)->first(array('value'));
			// Cache::forever($settings_prefix . $app, $setting['value']);
			// return $setting['value'];
		// });
		$app_info = App::where('name', '=', $app)->first();
		if($app != '' && is_null($app_info)) {
			return view('api.auth.forbidden');
		}
		return view('api.auth.login', ['app_info' => $app_info]);
	}

	public function postLogin(Request $request, Response $response)
	{

		$this->validate($request, ['username' => 'required', 'password' => 'required']);

		//对于带@和11位数字的，优先做email和phone处理，若失败则按username处理
		$username = $request->username;
		$password = $request->password;
		$credentials = array();
		if(strpos($username, '@') !== false) {
			$credentials = array('email' => $username, 'password' => $password);
		} elseif(preg_match('/^\d{11}$/', $username)) {
			$credentials = array('phone' => $username, 'password' => $password);
		}
		if(!empty($credentials) && !Auth::validate($credentials, $request->has('remember'))) {
			$credentials = array('username' => $username, 'password' => $password);
			if(!Auth::validate($credentials, $request->has('remember'))) {
				return redirect()->guest('/api/login?app=' . $request->app)
					->withInput()
					->withErrors('账户与密码不匹配，请重试！');
			}
		} else {
			$app = $request->app;
			$app_info = App::where('name', '=', $app)->first();

			$token_array['username'] = $request->username;
			$token_array['app'] = $app_info['app'];
			$token_array['app_secret'] = $app_info['app_secret'];
			$token_array['timestamp'] = time();
			$access_token = Crypt::encrypt($token_array);

			header('Location:' . $app_info['login_url'] . '?access_token=' . $access_token);
			exit;
				// $this->initRole($request, $response);
				// $this->loginLog($request, $credentials);
				// return redirect()->guest('/admin');
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
