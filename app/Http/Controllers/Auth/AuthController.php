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
use Cookie;
use Queue;
use Config;
use DB;
use App\Jobs\UserLog;
use App\Model\UserRole;
use Curl\Curl;
use EasyWeChat\Foundation\Application;

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

    public function getLogin(Request $request, Application $wechat)
    {
        $wechat->oauth->redirect();
        return view('auth.login')->with(array('loginFailed' => Cookie::get('login_failed')));
    }

    public function postLogin(Request $request, Response $response)
    {
        $this->validate($request, ['username' => 'required', 'password' => 'required']);

        if (false === $this->checkCaptcha($request->luotest_response)) {
            return redirect()->guest('/auth/login')
                ->withInput()
                ->withErrors('人机验证未通过，请重试！');
        }

        // 验证密码
        if (false === ($userId = $this->verifyPassword($request->username, $request->password, $credentials = array()))) {

            // 登陆失败记录，用于人机验证
            Cookie::queue('login_failed', 1, 3);

            return redirect()->guest('/auth/login')
                ->withInput()
                ->withErrors('账户与密码不匹配，请重试！');
        }

        Auth::loginUsingId($userId);

        // 登陆之后的操作
        $this->afterLogin($request, $response, $credentials);

        return redirect()->intended();
    }

    // 螺丝帽人机验证
    // link:https://luosimao.com/docs/api/56
    private function checkCaptcha($response)
    {
        if (!is_null($response)) {
            if ($response != '') {
                $curl = new Curl();
                $result = $curl->post('https://captcha.luosimao.com/api/site_verify', array(
                    'api_key' => env('CAPTCHA_API_KEY'),
                    'response' => $response,
                ));
                return ('success') == $result->res ? true : false;
            }
            return false;
        }
    }

    // 微信登陆之后的回调
    public function wechatCallback(Application $wechat, Request $request, Response $response)
    {
        $wechatUser = $wechat->oauth->user()->toArray();
        $user = Cache::get(Config::get('cache.wechat.openid') . $wechatUser['id']);
        if (empty($user)) {
            return redirect()->guest('/auth/login')
                ->withErrors('当前微信未绑定账户，请用账号登陆！');
        }
        Auth::loginUsingId($user['user_id']);
        $this->afterLogin($request, $response, array('wechat' => $wechatUser['nickname']));

        return redirect()->intended();
    }

    // 登陆之后的操作
    private function afterLogin(Request $request, Response $response, $credentials)
    {
        $this->cacheSettings();
        $this->cacheUsers();
        $this->cacheApps();
        $this->initRole($request, $response);
        $this->loginLog($request, $credentials);
        $this->cacheWechat();
        $this->accessToken();
    }

    // 初始化角色、应用、当前角色、当前应用
    private function initRole(Request $request, Response $response)
    {
        $rolesArray = UserRole::where('user_id', Auth::id())->get(array('app_id', 'role_id'))->toArray();
        foreach ($rolesArray as $v) {
            $apps[$v['app_id']] = Cache::get(Config::get('cache.apps') . $v['app_id']);
            $roles[$v['app_id']][$v['role_id']] = Cache::get(Config::get('cache.roles') . $v['role_id']);
        }
        $currentApp = Session::get('current_app', function() use ($apps) {
            $firstApp = reset($apps);
            Session::put('current_app', $firstApp);
            Session::put('current_app_title', $firstApp['title']);
            Session::put('current_app_id', $firstApp['id']);
            return $firstApp;
        });
        $currentRole = Session::get('current_role', function() use ($roles, $currentApp) {
            $firstRole = reset($roles[$currentApp['id']]);
            Session::put('current_role', $firstRole);
            Session::put('current_role_title', $firstRole['title']);
            Session::put('current_role_id', $firstRole['id']);
            return $firstRole;
        });

        Session::put('apps', $apps);
        Session::put('roles', $roles);
    }

    // 登录日志
    private function loginLog($request, $credentials) {
        $loginWay = key($credentials) . ' : ' . current($credentials);
        $ips = $request->ips();
        $ip = $ips[0];
        $ips = implode(',', $ips);
        $log = Queue::push(new UserLog(1, Auth::id(), 'S', '登录', $loginWay, '', $ip, $ips));
    }

    // 验证密码
    public function verifyPassword($username, $password, $credentials = array())
    {
        if (false !== strpos($username, '@')) {
            $credentials = array('email' => $username, 'password' => $password);
        } elseif (preg_match('/^\d{11}$/', $username)) {
            $credentials = array('phone' => $username, 'password' => $password);
        }
        if (!empty($credentials) && Auth::once($credentials)) {
            return Auth::id();
        }
        $credentials = array('username' => $username, 'password' => $password);
        if (Auth::once($credentials)) {
            return Auth::id();
        }

        return false;
    }

    // 注册
    public function getRegister()
    {
        return view('auth.register')->with(['accessToken' => parent::accessTokenByClientCredentials()]);
    }

    public function postRegister(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|unique:users,phone',
            'code' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        // 验证验证码
        $validateCode = $this->api
            ->with(['phone' => $request->phone, 'code' => $request->code, 'access_token' => $request->access_token])
            ->get('api/sms/validateCode');
        if (1 !== $validateCode['code']) {
            return redirect()->back()->withInput()->withErrors($validateCode['message']);
        }

        // 新增用户
        if ($user = User::create(array('username' => $request->phone, 'phone' => $request->phone, 'password' => bcrypt($request->password)))) {

            // cache新用户
            $this->cacheUsers($user['id']);

            // 增加默认角色
            $appRole = DB::table('apps')
                ->where('apps.name', env('DEFAULT_APP'))
                ->join('roles', 'apps.id', '=', 'roles.app_id')
                ->where('roles.name', env('DEFAULT_ROLE'))
                ->select('apps.id as app_id', 'roles.id as role_id')
                ->first();
            if (empty($appRole)) {
                return redirect()->back()->withInput()->withErrors('默认角色不存在，请联系管理员');
            }
            $userRole = DB::table('user_role')->insert(array(
                'user_id' => $user['id'],
                'app_id' => $appRole->app_id,
                'role_id' => $appRole->role_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ));

            session()->flash('success_message', '注册成功，请返回登陆');
        } else {
            return redirect()->back()->withInput()->withErrors('注册失败');
        }

        return redirect('/auth/login');
    }
}
