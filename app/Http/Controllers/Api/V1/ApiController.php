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

    public static function getUserId()
    {
        return (int)Authorizer::getResourceOwnerId();
    }

    public static function getClientId()
    {
        return Authorizer::getClientId();
    }

    public static function getAppId()
    {
        $app = Cache::get(Config::get('cache.clients') . self::getClientId());
        return $app['id'];
    }

}

