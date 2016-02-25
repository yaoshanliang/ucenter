<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Api;
use Cache;
use Config;
use Authorizer;
use Dingo\Api\Routing\Helpers;

class ApiController extends Controller
{
    use Helpers;

    private static $currentUserId;

}

