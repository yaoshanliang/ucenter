<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Authorizer;
use Dingo\Api\Routing\Helpers;

class OauthController extends Controller
{
    use Helpers;
    public function getAccessToken()
    {
        return $this->response->array(array('code' => 1, 'message' => '获取access_token成功', 'data' => Authorizer::issueAccessToken()));
    }
}
