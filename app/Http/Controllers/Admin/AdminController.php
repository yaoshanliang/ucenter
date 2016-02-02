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
        $settingsArray = Setting::all()->toArray();
        foreach($settingsArray as $k => $v) {
            $settings[$v['name']] = Cache::get(Config::get('cache.settings') . $v['name'], function() use ($v) {
                Cache::forever(Config::get('cache.settings') . $v['name'], $v['value']);
                return $v['value'];
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
