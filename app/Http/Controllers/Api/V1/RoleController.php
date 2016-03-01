<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Dingo\Api\Routing\Helpers;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

class RoleController extends ApiController
{
    use Helpers;

    public function getUserRole(Request $request)
    {
        dd(parent::$currentAppId);
    }
}
