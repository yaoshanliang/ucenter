<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

use App\Model\App;
use App\Model\Role;
use App\Model\User;
use App\Model\Permission;
use App\Model\Setting;
use App\Model\UserWechat;
use App\Model\UserRole;
use App\Model\RolePermission;
use Cache;
use Config;
use DB;
use Dingo\Api\Routing\Helpers;
use Auth;

abstract class Controller extends BaseController
{

    use DispatchesCommands, ValidatesRequests;
    use Helpers;

    function cacheSettings()
    {
        $settingsArray = Setting::all()->toArray();
        foreach($settingsArray as $k => $v) {
            $settings[$v['name']] = Cache::get(Config::get('cache.settings') . $v['name'], function() use ($v) {
                Cache::forever(Config::get('cache.settings') . $v['name'], $v['value']);
                return $v['value'];
            });
        }
    }

    function cacheApps($appId = 0)
    {
        $appsArray = App::where(function ($query) use ($appId) {
                if ($appId) {
                    $query->where('id', $appId);
                }
            })->get();
        foreach($appsArray as $v) {
            $apps[$v['id']] = Cache::get(Config::get('cache.apps') . $v['id'], function() use ($v) {
                $cacheData = array('id' => $v['id'], 'name' => $v['name'], 'title' => $v['title']);
                Cache::forever(Config::get('cache.apps') . $v['id'], $cacheData);
                Cache::forever(Config::get('cache.clients') . $v['name'], $cacheData);
                return $cacheData;
            });
        }
    }

    function cacheRoles($roleId = 0)
    {
        $rolesArray = Role::where(function ($query) use ($roleId) {
                if ($roleId) {
                    $query->where('id', $roleId);
                }
            })->get();
        $rolePermissionsArray = RolePermission::where(function ($query) use ($roleId) {
                if ($roleId) {
                    $query->where('role_id', $roleId);
                }
            })->get();
        foreach($rolesArray as $v) {
            $permissions = array();
            foreach ($rolePermissionsArray as $value) {
                if ($v->id == $value->role_id) {
                    $permissions[] = Cache::get(Config::get('cache.permissions') . $value->permission_id);
                }
            }
            $cacheData = array('id' => $v->id, 'name' => $v->name, 'title' => $v->title, 'permissions' => $permissions);
            Cache::forever(Config::get('cache.roles') . $v->id, $cacheData);
        }
    }

    function cachePermissions()
    {
        $permissionsArray = Permission::all();
        foreach ($permissionsArray as $v) {
            $cacheData = array('id' => $v->id, 'name' => $v->name, 'title' => $v->title);
            Cache::forever(Config::get('cache.permissions') . $v->id, $cacheData);
        }
    }

    function cacheUsers($user_id = 0)
    {
        // 用户详细信息字段
        $userFieldsArray = DB::table('user_fields')->get(array('id', 'name', 'title', 'description'));
        foreach ($userFieldsArray as $v) {
            $userFields[$v->id] = array('name' => $v->name, 'title' => $v->title);
        }

        // 用户基本信息，初始化详细信息
        $usersArray = User::where(function ($query) use ($user_id) {
                if ($user_id) {
                    $query->where('id', $user_id);
                }
            })
            ->get(array('id', 'username', 'email', 'phone'))->toArray();
        foreach ($usersArray as $v) {
            $users[$v['id']] = array('user_id' => $v['id'], 'username' => $v['username'], 'email' => $v['email'], 'phone' => $v['phone']);
            foreach ($userFields as $value) {
                $users[$v['id']]['details'][$value['name']] = array('title' => $value['title'], 'value' => '');
            }
        }

        // 用户详细信息
        $userInfoArray = DB::table('user_info')
            ->where(function ($query) use ($user_id) {
                if ($user_id) {
                    $query->where('id', $user_id);
                }
            })
            ->get(array('user_id', 'field_id', 'value'));
        foreach ($userInfoArray as $v) {
            $users[$v->user_id]['details'][$userFields[$v->field_id]['name']]['value'] = $v->value;
        }

        foreach($users as $v) {
            Cache::forever(Config::get('cache.users') . $v['user_id'], $v);
        }
    }

    // 缓存 用户-角色-权限
    function cacheUserRole()
    {
        $userRolesArray = UserRole::get();
        foreach ($userRolesArray as $v) {
            $roles[$v->app_id][$v->user_id][] = $v->role_id;
        }

        foreach ($roles as $key => $value) {
            foreach ($value as $k => $v) {
                $rolePermissions = array();
                $rolePermissions['user_id'] = $k;
                foreach ($v as $_v) {
                    $rolePermissions['roles'][] = Cache::get(Config::get('cache.roles') . $_v);
                }
                Cache::forever(Config::get('cache.user_role.app_id') . $key . ':user_id:' . $k, $rolePermissions);
            }
        }
    }

    // 缓存微信
    function cacheWechat()
    {
        $usersArray = UserWechat::get(array('user_id', 'unionid', 'openid', 'nickname', 'sex', 'language', 'city', 'province', 'country', 'headimgurl'));
        foreach ($usersArray as $v) {
            Cache::forever(Config::get('cache.wechat.openid') . $v['openid'], $v->toArray());
            Cache::forever(Config::get('cache.wechat.user_id') . $v['user_id'], $v['openid']);
        }
    }

    // 获取access_token并缓存，失效时重新请求，即将过期时刷新
    public function accessToken()
    {
        if (!($accessToken = Cache::get(Config::get('cache.api.access_token') . Auth::id()))) {

            // 获取授权码
            $authCode = $this->api
                ->with(['client_id' => env('client_id'), 'response_type' => 'code', 'redirect_uri' => env('redirect_uri')])
                ->get('api/oauth/getAuthCode');

            // 获取access_token
            $accessToken = $this->api
                ->with(['client_id' => env('client_id'), 'client_secret' => env('client_secret'),
                    'grant_type' => 'authorization_code', 'redirect_uri' => env('redirect_uri'), 'code' => $authCode])
                ->post('api/oauth/getAccessToken');
            $accessToken = $accessToken['data'];
            $accessToken['timestamp'] = time();

            Cache::put(Config::get('cache.api.access_token') . Auth::id(), $accessToken, $accessToken['expires_in'] / 60);
        } elseif ( time() - $accessToken['timestamp'] < 10 * 60) {

            // 刷新access_token
            $accessToken = $this->api
                ->with(['client_id' => env('client_id'), 'client_secret' => env('client_secret'),
                    'grant_type' => 'refresh_token', 'refresh_token' => $accessToken['refresh_token']])
                ->post('api/oauth/getAccessToken');
            $accessToken = $accessToken['data'];
            $accessToken['timestamp'] = time();

            Cache::put(Config::get('cache.api.access_token') . Auth::id(), $accessToken, $accessToken['expires_in'] / 60);
        }
        return $accessToken['access_token'];
    }

    // ClientCredentialsGrant，用于未登陆时获取的access_token
    public function accessTokenByClientCredentials()
    {
        $accessToken = $this->api
            ->with(['client_id' => env('client_id'), 'client_secret' => env('client_secret'),
                'grant_type' => 'client_credentials'])
            ->post('api/oauth/getAccessToken');

        return $accessToken['data']['access_token'];
    }
}
