<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Api;
use Cache;
use Config;
use Authorizer;
use App\Exceptions\ApiException;
use Validator;

class ApiController extends Controller
{
    /**
     * 获取用户id
     *
     * @param string $access_token access_token
     * @return int $user_id 用户id
     */
    public static function getUserId()
    {
        return (int)Authorizer::getResourceOwnerId();
    }

    /**
     * 获取客户端id
     *
     * @param string $access_token access_token
     * @return string $client_id 客户端id
     */
    public static function getClientId()
    {
        return Authorizer::getClientId();
    }

    /**
     * 获取app id
     *
     * @param string $access_token access_token
     * @return int $app_id app id
     */
    public static function getAppId()
    {
        $app = Cache::get(Config::get('cache.clients') . self::getClientId());

        return $app['id'];
    }

    /**
     * 验证参数
     *
     * @param array $input 需要验证的参数
     * @param array $rules 验证规则
     * @return ApiException
     */
     public function apiValidate($input, $rules)
     {
         $validator = Validator::make($input, $rules);
         if ($validator->fails()) {
             throw new ApiException($validator->messages()->first());
         }

         return true;
     }


}
