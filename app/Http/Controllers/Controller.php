<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

use App\App;
use App\Role;
use Cache;
abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;

	function cacheApps() {
		$apps_prefix = 'apps:';
		$apps_array = App::all();
		foreach($apps_array as $v) {
			$apps[$v['id']] = Cache::get($apps_prefix . $v['id'], function() use ($v, $apps_prefix) {
				$cache_data = array('id' => $v['id'], 'name' => $v['name'], 'title' => $v['title']);
				Cache::forever($apps_prefix . $v['id'], $cache_data);
				return $cache_data;
			});
		}
	}

	function cacheRoles() {
		$roles_prefix = 'roles:';
		$roles_array = Role::all();
		foreach($roles_array as $v) {
			$roles[$v['id']] = Cache::get($roles_prefix . $v['id'], function() use ($v, $roles_prefix) {
				$cache_data = array('id' => $v['id'], 'name' => $v['name'], 'title' => $v['title']);
				Cache::forever($roles_prefix . $v['id'], $cache_data);
				return $cache_data;
			});
		}
	}
}
