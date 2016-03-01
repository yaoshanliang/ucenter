<?php namespace App\Http\Controllers\Api;

use Input, Redirect;
use Auth;
use Crypt;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use Session;
use Cache;
use Queue;
use App\App;
use App\Commands\UserLog;
use App\Services\Api;
class LoginController extends Controller
{
}
