<?php
namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use Cache;
use Config;
use Queue;
use Validator;
use App\Services\Api;
use App\Model\User;
use App\Model\UserFields;
use App\Model\UserInfo;
use Dingo\Api\Routing\Helpers;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

class UserController extends Controller
{
    use Helpers;

    // 当前用户的id
    private static $currentUserId;

    public function __construct()
    {
        self::$currentUserId = (int)Authorizer::getResourceOwnerId();
    }

    // 获取用户信息，没有user_id参数时则为当前用户
    public function getUserInfo(Request $request)
    {
        $userId = empty($request->has('user_id')) ? self::$currentUserId : (int)$request->get('user_id');
        $data = Cache::get(Config::get('cache.users') . $userId);
        $code = 1 AND $message = '获取用户信息成功';

        return $this->response->array(compact('code', 'message', 'data'));
    }

    public function edit(Request $request)
    {
        $user = Cache::get(Config::get('cache.users') . self::$currentUserId);
        foreach ($request->all() as $k => $v) {
            switch ($k) {
                case 'username' :
                    $validator = Validator::make(array($k => $request->$k), ['username' => 'required|unique:users,username,'.self::$currentUserId.'']);
                    break;

                case 'email' :
                    $validator = Validator::make(array($k => $request->$k), ['email' => 'required|email|unique:users,email,'.self::$currentUserId.'']);
                    break;

                default :
                    $userFieldsArray = UserFields::where('name', $k)->first(array('id', 'validation'));
                    if (!empty($userFieldsArray)) {
                        $validator = Validator::make(array($k => $request->$k), [$k => $userFieldsArray['validation']]);
                    }
                    break;
            }

            if (isset($validator)) {

                // 返回验证失败信息
                if ($validator->fails()) {
                    $message = $validator->messages()->first();
                    return $this->response->array(array('code' => 0, 'message' => $message));
                }

                // 更新数据库
                switch ($k) {
                    case 'username' :
                    case 'email' :
                        if ($user[$k] != $request->$k) {
                            $result = User::where('id', self::$currentUserId)->update(array($k => $request->$k));
                            $user[$k] = $request->$k;
                            $isEdit = true;
                        }
                    break;

                    default :
                        if (isset($user['details'][$k]) && $user['details'][$k]['value'] != $request->$k) {
                            $result = UserInfo::where('user_id', self::$currentUserId)->where('field_id', $userFieldsArray['id'])->update(array('value' => $request->$k));
                            $user['details'][$k]['value'] = $request->$k;
                            $isEdit = true;
                        }
                    break;
                }
            }
        }

        // 更新cache
        if (isset($isEdit)) {
            Cache::forever(Config::get('cache.users') . self::$currentUserId, $user);
            return $this->response->array(array('code' => 1, 'message' => '修改成功', 'data' => $user));
        } else {
            return $this->response->array(array('code' => 0, 'message' => '未做修改'));
        }
    }
}
