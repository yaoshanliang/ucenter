<?php
namespace App\Http\Controllers\Oauth;

use App\Http\Controllers\Controller;
use Authorizer;
use Auth;
use Request;

class OauthController extends Controller
{
    public function getAuthorize()
    {
        $authParams = Authorizer::getAuthCodeRequestParams();
        $formParams = array_except($authParams,'client');
        $formParams['client_id'] = $authParams['client']->getId();
        $formParams['scope'] = implode(config('oauth2.scope_delimiter'), array_map(function ($scope) {
            return $scope->getId();
        }, $authParams['scopes']));

       return view('oauth.authorize', ['params' => $formParams, 'client' => $authParams['client']]);
    }

    public function postAuthorize()
    {
        $params = Authorizer::getAuthCodeRequestParams();
        $params['user_id'] = Auth::id();
        $redirectUri = '/';

        if (Request::has('approve')) {
            $redirectUri = Authorizer::issueAuthCode('user', $params['user_id'], $params);
        }

        if (Request::has('deny')) {
            $redirectUri = Authorizer::authCodeRequestDeniedRedirectUri();
        }

        return redirect($redirectUri);
    }

}
