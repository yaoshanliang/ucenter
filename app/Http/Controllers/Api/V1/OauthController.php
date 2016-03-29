<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Authorizer;
use Dingo\Api\Routing\Helpers;
use Auth;

class OauthController extends Controller
{
    /**
     * 获取access_token
     *
     * @return apiReturn
     */
    public function getAccessToken()
    {
        return Api::apiReturn(SUCCESS, '获取access_token成功', Authorizer::issueAccessToken());
    }

    /**
     * 获取授权码
     *
     * @return apiReturn
     */
    public function getAuthCode()
    {
        $authParams = Authorizer::getAuthCodeRequestParams();
        $formParams = array_except($authParams,'client');
        $formParams['client_id'] = $authParams['client']->getId();
        $formParams['scope'] = implode(config('oauth2.scope_delimiter'), array_map(function ($scope) {
            return $scope->getId();
        }, $authParams['scopes']));
        $params['user_id'] = Auth::id();//需要登陆

        $redirectUri = Authorizer::issueAuthCode('user', $params['user_id'], $params);

        return substr(strstr($redirectUri, '?code'), 6);
    }
}
