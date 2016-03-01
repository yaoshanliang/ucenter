<?php namespace App\Http\Controllers\Api\V1;

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
use App\Http\Model\App;
use App\Jobs\UserLog;
use App\Services\Api;
use App\Model\User;
use App\Providers\OAuthServiceProvider;

use Dingo\Api\Routing\Helpers;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
class AppController extends Controller {

    use Helpers;

}
