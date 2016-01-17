<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

use App\Model\App;
use App\Model\Role;
use Cache;
abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;

	function cacheApps() {
		$apps_array = App::all();
		foreach($apps_array as $v) {
			$apps[$v['id']] = Cache::get(env('CACHE_APPS_PREFIX') . $v['id'], function() use ($v) {
				$cache_data = array('id' => $v['id'], 'name' => $v['name'], 'title' => $v['title']);
				Cache::forever(env('CACHE_APPS_PREFIX') . $v['id'], $cache_data);
				return $cache_data;
			});
		}
	}

	function cacheRoles() {
		$roles_array = Role::all();
		foreach($roles_array as $v) {
			$roles[$v['id']] = Cache::get(env('CACHE_ROLES_PREFIX') . $v['id'], function() use ($v) {
				$cache_data = array('id' => $v['id'], 'name' => $v['name'], 'title' => $v['title']);
				Cache::forever(env('CACHE_ROLES_PREFIX'). $v['id'], $cache_data);
				return $cache_data;
			});
		}
	}
}
