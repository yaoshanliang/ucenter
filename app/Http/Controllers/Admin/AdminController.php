<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Model\App;
use App\Model\Setting;
use App\Model\Role;
use Redirect, Input, Auth;
use Cache;
use Session;
use Config;

class AdminController extends Controller {

	public function __construct()
	{
		$settings_array = array('site_name', 'site_url', 'site_sub_name', 'site_description', 'copyright', 'support_email',
								'bei_an', 'tong_ji', 'page_size', 'expire');
		$settings_prefix = 'settings:';
		foreach($settings_array as $k => $v) {
			$settings[$v] = Cache::get($settings_prefix . $v, function() use ($v, $settings_prefix) {
				$setting = Setting::where('name', $v)->first(array('value'));
				Cache::forever($settings_prefix . $v, $setting['value']);
				return $setting['value'];
			});
		}
	}

	public function index()
	{
        $apps = Session::get('apps');
        $roles = Session::get('roles');
		return view('admin.index')->with(compact('apps', 'roles'));
	}

    public function forbidden()
    {
        $apps = Session::get('apps');
        $roles = Session::get('roles');
        $currentRole = Session::get('current_role');
        if (in_array($currentRole['name'], Config::get('entrust.admin_role'))) {
            return redirect('/admin/index');
        }
		return view('admin.forbidden')->with(compact('apps', 'roles'));
    }
}
