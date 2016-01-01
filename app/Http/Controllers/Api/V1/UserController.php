<?php namespace App\Http\Controllers\Api\V1;

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
use App\Http\Model\App;
use App\Jobs\UserLog;
use App\Services\Api;
use App\Model\User;
use App\Providers\OAuthServiceProvider;

use Dingo\Api\Routing\Helpers;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
class UserController extends Controller {

    use Helpers;

    // 当前用户的id
    private static $current_user_id;

    public function __construct() {
        self::$current_user_id = (int)Authorizer::getResourceOwnerId();
    }

    // 获取用户信息，没有user_id参数时则为当前用户
    public function getUserInfo(Request $request)
    {
        $user_id = empty($request->has('user_id')) ? self::$current_user_id : (int)$request->get('user_id');
        $data = User::getUserInfo($user_id);
        return $this->response->errorNotFound();
        $code = 1 AND $message = array('SUCCESS' => '获取用户信息成功');

        return $this->response->array(compact('code', 'message', 'data'));
    }
}
