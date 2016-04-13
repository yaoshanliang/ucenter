<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use Cache;
use Config;
use Queue;
use DB;
use Validator;
use App\Services\Api;
use App\Model\User;
use App\Model\UserFields;
use App\Model\UserInfo;

class UserController extends ApiController
{
    /**
     * 注册
     *
     * @param string $phone 手机号
     * @param string $password 密码
     * @param string $code 验证码
     */
    public function postUser(Request $request)
    {
        $this->_beforeRegister($request->phone, $request->password, $request->code);

        $user = User::create(['username' => $request->phone, 'phone' => $request->phone, 'password' => bcrypt($request->password)]);

        $this->_afterRegister($user);

        return Api::apiReturn(SUCCESS, '注册成功', $user->toArray());
    }

    /**
     * 注册之前的操作
     *
     * @param string $phone 手机号
     * @param string $password 密码
     * @param string $code 验证码
     */
    protected function _beforeRegister($phone, $password, $code)
    {
        Validator::extend('validate_code', function($attribute, $value, $parameters) use ($code) {
            return (Cache::get(Config::get('cache.sms.code') . $value) == $code);
        });

        $this->apiValidate(compact('phone', 'password', 'code'), ['phone' => 'required|size:11|unique:users,phone|validate_code', 'password' => 'required|min:6', 'code' => 'required|size:6']);
    }

    /**
     * 注册之后的操作
     *
     * @param array $user 用户信息
     */
    protected function _afterRegister(&$user)
    {
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
            return Api::apiReturn(ERROR, '默认角色不存在');
        }
        $userRole = DB::table('user_role')->insert(array(
            'user_id' => $user['id'],
            'app_id' => $appRole->app_id,
            'role_id' => $appRole->role_id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));

        $user['user_id'] = $user['id'];
        unset($user['id']);
        unset($user['updated_at']);
    }

    /**
     * 获取当前用户信息
     *
     * @return apiReturn
     */
    public function getUser(Request $request)
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
    public function putUser(Request $request)
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
