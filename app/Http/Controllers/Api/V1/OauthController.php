<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Authorizer;
use Dingo\Api\Routing\Helpers;

class OauthController extends Controller
{
    use Helpers;

    // 获取access_token
    public function getAccessToken()
    {
        return $this->response->array(array('code' => 1, 'message' => '获取access_token成功', 'data' => Authorizer::issueAccessToken()));
    }

    // 获取授权码
    public function getAuthCode()
    {
        $authParams = Authorizer::getAuthCodeRequestParams();
        $formParams = array_except($authParams,'client');
        $formParams['client_id'] = $authParams['client']->getId();
        $formParams['scope'] = implode(config('oauth2.scope_delimiter'), array_map(function ($scope) {
            return $scope->getId();
        }, $authParams['scopes']));
        $params['user_id'] = 1000;

        $redirectUri = Authorizer::issueAuthCode('user', $params['user_id'], $params);

        return substr(strstr($redirectUri, '?code'), 6);
    }
}
