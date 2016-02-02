<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

use App\Model\App;
use App\Model\Role;
use Cache;
use Config;
abstract class Controller extends BaseController {

    use DispatchesCommands, ValidatesRequests;

    function cacheApps($app_id) {
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

    function cacheRoles($role_id) {
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
}
