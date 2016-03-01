<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use JWTAuth;
use App\User;
use Cache;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticateController extends Controller
{
    use Helpers;

}
