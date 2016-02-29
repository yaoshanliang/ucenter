<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;
use App\Model\User;
use Cache;
use Config;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    // 通过手机号找回密码
    public function getPhone()
    {
        return view('auth.password')->with(['accessToken' => parent::accessTokenByClientCredentials()]);
    }

    public function postPhone(Request $request, Response $response)
    {
        $this->validate($request, [
            'phone' => 'required',
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

        // 判断用户是否存在
        $user = User::where('phone', $request->phone)->first();
        if (empty($user)) {
            return redirect()->back()->withInput($request->only('phone'))->withErrors('未找到该用户');
        }

        // 更新数据库
        $user->password = bcrypt($request->password);
        $user->save();
        session()->flash('success_message', '密码重置成功，请返回登陆');

        return redirect('/auth/login');
    }
}
