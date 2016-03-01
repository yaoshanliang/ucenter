<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiController;
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

class UserController extends ApiController
{
    use Helpers;

    // 获取用户信息，没有user_id参数时则为当前用户
    public function getUserInfo(Request $request)
    {
        $userId = empty($request->has('user_id')) ? parent::$currentUserId : (int)$request->get('user_id');
        $data = Cache::get(Config::get('cache.users') . $userId);

        if (empty($data)) {
            return $this->response->array(array('code' => 0, 'message' => '不存在此用户'));
        } else {
            return $this->response->array(array('code' => 1, 'message' => '获取用户信息成功', 'data' => $data));
        }
    }

    // 更新用户信息
    public function edit(Request $request)
    {
        $user = Cache::get(Config::get('cache.users') . parent::$currentUserId);
        foreach ($request->all() as $k => $v) {
            switch ($k) {
                case 'username' :
                    $validator = Validator::make(array($k => $request->$k), ['username' => 'required|unique:users,username,'.parent::$currentUserId]);
                    break;

                case 'email' :
                    $validator = Validator::make(array($k => $request->$k), ['email' => 'required|email|unique:users,email,'.parent::$currentUserId]);
                    break;

                case 'phone' :
                    Validator::extend('validate_code', function($attribute, $value, $parameters) {
                        return Cache::get(Config::get('cache.sms.validated') . $value) ? true : false;
                    });
                    $validator = Validator::make(array($k => $request->$k), ['phone' => 'required|size:11|unique:users,phone,'.parent::$currentUserId.'|validate_code'], ['validate_code' => '手机号验证失败']);
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
                    case 'phone' :
                        if ($user[$k] != $request->$k) {
                            $result = User::where('id', parent::$currentUserId)->update(array($k => $request->$k));
                            $user[$k] = $request->$k;
                            $isEdit = true;
                        }
                    break;

                    default :
                        if (isset($user['details'][$k]) && $user['details'][$k]['value'] != $request->$k) {
                            $result = UserInfo::where('user_id', parent::$currentUserId)->where('field_id', $userFieldsArray['id'])->update(array('value' => $request->$k));
                            $user['details'][$k]['value'] = $request->$k;
                            $isEdit = true;
                        }
                    break;
                }
            }
        }

        if (isset($isEdit)) {

            // 更新cache
            Cache::forever(Config::get('cache.users') . parent::$currentUserId, $user);

            return $this->response->array(array('code' => 1, 'message' => '修改成功', 'data' => $user));
        } else {
            return $this->response->array(array('code' => 0, 'message' => '未做修改'));
        }
    }
}
