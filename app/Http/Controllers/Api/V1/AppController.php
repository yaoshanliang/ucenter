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
class AppController extends Controller {

    use Helpers;

    // 当前用户的id
    private static $current_user_id;

    public function __construct() {
        self::$current_user_id = (int)Authorizer::getResourceOwnerId();
    }

    public function setCurrentApp(Request $request) {
        $app = Cache::get('apps:' . $request->app_id);
		Session::put('current_app', $app['name']);
		Session::put('current_app_title', $app['title']);
		Session::put('current_app_id', $app['id']);

        $code = 1 AND $message = '获取用户信息成功';

        return $this->response->array(compact('code', 'message', 'data'));
    }
}
