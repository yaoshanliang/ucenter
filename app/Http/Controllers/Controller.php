<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

use App\Model\App;
use App\Model\Role;
use App\Model\User;
use Cache;
use Config;
use DB;
abstract class Controller extends BaseController
{

    use DispatchesCommands, ValidatesRequests;

    function cacheApps($app_id)
    {
        $appsArray = App::all();
        foreach($appsArray as $v) {
            $apps[$v['id']] = Cache::get(Config::get('cache.apps') . $v['id'], function() use ($v) {
                $cacheData = array('id' => $v['id'], 'name' => $v['name'], 'title' => $v['title']);
                Cache::forever(Config::get('cache.apps') . $v['id'], $cacheData);
                return $cacheData;
            });
        }
        return $apps[$app_id];
    }

    function cacheRoles($role_id)
    {
        $rolesArray = Role::all();
        foreach($rolesArray as $v) {
            $roles[$v['id']] = Cache::get(Config::get('cache.roles') . $v['id'], function() use ($v) {
                $cacheData = array('id' => $v['id'], 'name' => $v['name'], 'title' => $v['title']);
                Cache::forever(Config::get('cache.roles') . $v['id'], $cacheData);
                return $cacheData;
            });
        }
        return $roles[$role_id];
    }

    function cacheUsers()
    {
        // 用户详细信息字段
        $userFieldsArray = DB::table('user_fields')->get(array('id', 'name', 'title', 'description'));
        foreach ($userFieldsArray as $v) {
            $userFields[$v->id] = array('name' => $v->name, 'title' => $v->title);
        }

        // 用户基本信息，初始化详细信息
        $usersArray = User::get(array('id', 'username', 'email', 'phone'))->toArray();
        foreach ($usersArray as $v) {
            $users[$v['id']] = array('user_id' => $v['id'], 'username' => $v['username'], 'email' => $v['email'], 'phone' => $v['phone']);
            foreach ($userFields as $value) {
                $users[$v['id']]['details'][$value['name']] = array('title' => $value['title'], 'value' => '');
            }
        }

        // 用户详细信息
        $userInfoArray = DB::table('user_info')->get(array('user_id', 'field_id', 'value'));
        foreach ($userInfoArray as $v) {
            $users[$v->user_id]['details'][$userFields[$v->field_id]['name']]['value'] = $v->value;
        }

        foreach($users as $v) {
            Cache::forever(Config::get('cache.users') . $v['user_id'], $v);
        }
    }
}
