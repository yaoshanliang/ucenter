<?php
namespace App\Http\Controllers\Home;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\User;
use Auth;
use Cache;
use Config;

class UserController extends Controller
{
    public function index()
    {
        $user = Cache::get(Config::get('cache.users') . Auth::id());
        return view('home.user.index')->withUser($user);
    }
}
