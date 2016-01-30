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
use App\Model\UserRole;

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
		return view('auth.login');
	}

	public function postLogin(Request $request, Response $response)
	{
		$this->validate($request, ['username' => 'required', 'password' => 'required']);

		$username = $request->username;
		$password = $request->password;
		$credentials = array();
		if (strpos($username, '@') !== false) {
			$credentials = array('email' => $username, 'password' => $password);
		} elseif (preg_match('/^\d{11}$/', $username)) {
			$credentials = array('phone' => $username, 'password' => $password);
		}
		if (!empty($credentials) && Auth::attempt($credentials, $request->has('remember'))) {
			$this->initRole($request, $response);
			$this->loginLog($request, $credentials);
            return redirect()->intended();
		}
		$credentials = array('username' => $request->username, 'password' => $request->password);
		if (Auth::attempt($credentials, $request->has('remember'))) {
			$this->initRole($request, $response);
			$this->loginLog($request, $credentials);
            return redirect()->intended();
		} else {
			return redirect()->guest('/auth/login')
				->withInput()
				->withErrors('账户与密码不匹配，请重试！');
		}
	}

	// 初始化角色、应用、当前角色、当前应用
	private function initRole(Request $request, Response $response)
	{
        $rolesArray = UserRole::where('user_id', Auth::id())->get(array('app_id', 'role_id'))->toArray();
		foreach ($rolesArray as $v) {
			$apps[$v['app_id']] = Cache::get('apps:' . $v['app_id'], function() { $this->cacheApps(); });
			$roles[$v['app_id']][$v['role_id']] = Cache::get('roles:' . $v['role_id'], function() { $this->cacheRoles(); });
		}
		$currentApp = Session::get('current_app', function() use ($apps) {
			$firstApp = reset($apps);
			Session::put('current_app', $firstApp);
			Session::put('current_app_title', $firstApp['title']);
			Session::put('current_app_id', $firstApp['id']);
			return $firstApp;
		});
		$currentRole = Session::get('current_role', function() use ($roles, $currentApp) {
			$firstRole = $roles[$currentApp['id']];
			Session::put('current_role', $firstRole);
			Session::put('current_role_title', $firstRole[$currentApp['id']]['title']);
			Session::put('current_role_id', $firstRole[$currentApp['id']]['id']);
			return $firstRole;
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
