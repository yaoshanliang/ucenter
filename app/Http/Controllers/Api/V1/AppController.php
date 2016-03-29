<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use Cache;
use Config;
use Queue;
use DB;
use App\Model\App;
use Dingo\Api\Routing\Helpers;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

class AppController extends ApiController
{
    // 重新生成密钥
    public function putSecret(Request $request)
    {
        $secret = md5(uniqid(time() . rand(1000, 9999)));

        return Api::apiReturn(SUCCESS, '获取新密钥成功', $secret);
    }

}
