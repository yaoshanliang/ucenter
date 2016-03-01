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
    }

    public function index()
    {
        $apps = Session::get('apps');
        $roles = Session::get('roles');
        return view('admin.index')->with(compact('apps', 'roles'));
    }
}
