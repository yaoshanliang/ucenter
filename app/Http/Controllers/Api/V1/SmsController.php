<?php
namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use Cache;
use Queue;
use Config;
use App\Services\Api;
use App\Model\User;
use Dingo\Api\Routing\Helpers;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use PhpSms;

use Toplan\PhpSms\Sms;
class SmsController extends Controller
{
    use Helpers;

    // 当前用户的id
    private static $currentUserId;

    public function __construct()
    {
        self::$currentUserId = (int)Authorizer::getResourceOwnerId();
    }

    // 发送验证码
    public function sendCode(Request $request)
    {
        if ( 11 != strlen($phone = $request->get('phone'))) {
            return $this->response->array(array('code' => 0, 'message' => '手机号不合法'));
        }

        $code = rand(100000, 999999);
        Cache::put(Config::get('cache.sms.code') . $phone, $code, 5);
        // PhpSms::queue(false);
        // PhpSms::make()->to('18896581232')->content('【签名】这是短信内容...')->send();
        // PhpSms::make()->to('18896581232')->template('Luosimao', 'xxx')->data(['12345', 5])->send();
        return $this->response->array(array('code' => 1, 'message' => '发送成功'));
    }

    // 验证验证码
    public function validateCode(Request $request)
    {
        if ( 11 != strlen($phone = $request->get('phone'))) {
            return $this->response->array(array('code' => 0, 'message' => '手机号不合法'));
        }
        if ( 0 == strlen($code = $request->get('code'))) {
            return $this->response->array(array('code' => 0, 'message' => '验证码不合法'));
        }

        if (Cache::get(Config::get('cache.sms.code') . $phone) != $code) {
            return $this->response->array(array('code' => 0, 'message' => '验证失败，验证码不匹配'));
        } else {
            Cache::put(Config::get('cache.sms.validated') . $phone, 1, 1);
            return $this->response->array(array('code' => 1, 'message' => '验证成功'));
        }
    }
}
