<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use Cache;
use Config;
use Queue;
use App\Services\Api;
use App\Model\User;
use App\Model\UserFields;
use App\Model\UserInfo;

class UserController extends ApiController
{
    /**
     * 获取当前用户信息
     *
     * @return apiReturn
     */
    public function getInfo(Request $request)
    {
        $data = Cache::get(Config::get('cache.users') . parent::getUserId());

        return Api::apiReturn(SUCCESS, '获取用户信息成功', $data);
    }

    /**
     * 获取当前应用当前用户的角色
     *
     * @return apiReturn
     */
    public function getRole(Request $request)
    {
        $roles = Cache::get(Config::get('cache.user_role.app_id') . parent::getAppId() . ':user_id:' . parent::getUserId());

        if (empty($roles['roles'])) {
            return Api::apiReturn(SUCCESS, '当前用户没有角色');
        }

        return Api::apiReturn(SUCCESS, '获取用户角色成功', $data);
    }

    /**
     * 获取当前应用当前用户的权限
     *
     * @return apiReturn
     */
    public function getPermission(Request $request)
    {
        $roles = Cache::get(Config::get('cache.user_role.app_id') . parent::getAppId() . ':user_id:' . parent::getUserId());

        if (empty($roles['roles'])) {
            return Api::apiReturn(SUCCESS, '当前用户没有权限');
        }
        $data['user_id'] = $roles['user_id'];
        $permissions = array();
        foreach ($roles['roles'] as $value) {
            foreach ($value['permissions'] as $v) {
                $permissions[$v['id']] = $v;
            }
        }

        if (empty($permissions)) {
            return Api::apiReturn(SUCCESS, '当前用户没有权限');
        }

        $data['permissions'] = array_values($permissions);

        return Api::apiReturn(SUCCESS, '当前用户权限成功', $data);
    }

    /**
     * 更新用户信息
     *
     * @return apiReturn
     */
    public function putInfo(Request $request)
    {
        $user = Cache::get(Config::get('cache.users') . parent::getUserId());

        $isEdit = false;

        foreach ($request->all() as $k => $v) {
            switch ($k) {
                case 'username' :
                    $validator = $this->apiValidate(array($k => $request->$k), ['username' => 'required|unique:users,username,'.parent::getUserId()]);
                    break;

                case 'email' :
                    $validator = $this->apiValidate(array($k => $request->$k), ['email' => 'required|email|unique:users,email,'.parent::getUserId()]);
                    break;

                case 'phone' :
                    Validator::extend('validate_code', function($attribute, $value, $parameters) {
                        return Cache::get(Config::get('cache.sms.validated') . $value) ? true : false;
                    });
                    $validator = $this->apiValidate(array($k => $request->$k), ['phone' => 'required|size:11|unique:users,phone,'.parent::getUserId().'|validate_code']);
                    break;

                default :
                    $userFieldsArray = UserFields::where('name', $k)->first(array('id', 'validation'));
                    if (!empty($userFieldsArray)) {
                        $validator = $this->apiValidate(array($k => $request->$k), [$k => $userFieldsArray['validation']]);
                    }
                    break;
            }

            if (isset($validator)) {

                // 更新数据库
                switch ($k) {
                    case 'username' :
                    case 'email' :
                    case 'phone' :
                        if ($user[$k] != $request->$k) {
                            $result = User::where('id', parent::getUserId())->update(array($k => $request->$k));
                            $user[$k] = $request->$k;
                            $isEdit = true;
                        }
                    break;

                    default :
                        if (isset($user['details'][$k]) && $user['details'][$k]['value'] != $request->$k) {
                            $result = UserInfo::where('user_id', parent::getUserId())->where('field_id', $userFieldsArray['id'])->update(array('value' => $request->$k));
                            $user['details'][$k]['value'] = $request->$k;
                            $isEdit = true;
                        }
                    break;
                }
            }
        }

        if (true === $isEdit) {

            // 更新cache
            Cache::forever(Config::get('cache.users') . parent::getUserId(), $user);

            return Api::apiReturn(SUCCESS, '修改成功', $user);
        } else {
            return Api::apiReturn(ERROR, '未做修改');
        }
    }
}
