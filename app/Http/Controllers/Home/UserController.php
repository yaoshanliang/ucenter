<?php
namespace App\Http\Controllers\Home;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        return view('home.user.index');
    }
}
