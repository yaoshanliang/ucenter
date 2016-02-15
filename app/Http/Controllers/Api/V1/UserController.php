<?php
namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use Cache;
use Queue;
use App\Services\Api;
use App\Model\User;
// use App\Providers\OAuthServiceProvider;
use Dingo\Api\Routing\Helpers;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

class UserController extends Controller
{
    use Helpers;

    // 当前用户的id
    private static $currentUserId;

    public function __construct() {
        self::$currentUserId = (int)Authorizer::getResourceOwnerId();
    }

    // 获取用户信息，没有user_id参数时则为当前用户
    public function getUserInfo(Request $request) {
        $user_id = empty($request->has('user_id')) ? self::$currentUserId : (int)$request->get('user_id');
        $data = User::getUserInfo($user_id);
        $code = 1 AND $message = '获取用户信息成功';

        return $this->response->array(compact('code', 'message', 'data'));
    }
}
