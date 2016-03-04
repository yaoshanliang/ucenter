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
    use Helpers;

    // 重新生成密钥
    public function updateSecret(Request $request)
    {
        $secret = md5(uniqid(time() . rand(1000, 9999)));

        return $this->response->array(array('code' => 1, 'message' => '获取新密钥成功', 'data' => $secret));
    }

}
