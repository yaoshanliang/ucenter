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
    // 发送验证码
    public function sendCode(Request $request)
    {
        $beforeSend = $this->beforeSend($request);
        if (1 !== $beforeSend['code']) {
            return $this->response->array(array('code' => 0, 'message' => $beforeSend['message']));
        }

        $code = rand(100000, 999999);
        Cache::put(Config::get('cache.sms.code') . $request->phone, $code, 5);
        PhpSms::queue(false);
        $result = PhpSms::make()->to($request->phone)->content('您好，您的验证码是：' . $code)->send();

        if ( true === $result['success']) {
            return $this->response->array(array('code' => 1, 'message' => '发送成功'));
        } else {
            return $this->response->array(array('code' => 0, 'message' => '发送失败', 'data' => $result['logs']));
        }
    }

    // 发送内容短信
    public function sendContent(Request $request)
    {
        $beforeSend = $this->beforeSend($request);
        if (1 !== $beforeSend['code']) {
            return $this->response->array(array('code' => 0, 'message' => $beforeSend['message']));
        }

        PhpSms::queue(false);
        $result = PhpSms::make()->to($request->phone)->content($request->content)->send();

        if ( true === $result['success']) {
            return $this->response->array(array('code' => 1, 'message' => '发送成功'));
        } else {
            return $this->response->array(array('code' => 0, 'message' => '发送失败', 'data' => $result['logs']));
        }
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

    // 发送前的校验
    private function beforeSend(Request $request)
    {
        if ( 11 != strlen($request->get('phone'))) {
            return array('code' => 0, 'message' => '手机号不合法');
        }

        // 手机号和用户id两种方式计数
        $count = empty(parent::getUserId()) ? Cache::get(Config::get('cache.sms.count.phone') . $request->phone) :
            Cache::get(Config::get('cache.sms.count.user_id') . parent::getUserId());

        if (is_null($count)) {
            empty(parent::getUserId()) ? Cache::put(Config::get('cache.sms.count.phone') . $request->phone, 1, 60) :
                Cache::put(Config::get('cache.sms.count.user_id') . parent::getUserId(), 1, 60);
        } else {
            if (!in_array($request->phone, Config::get('phpsms.whiteList')) && $count > 3) {
                return array('code' => 0, 'message' => '超过每小时三次限制');
            }
            empty(parent::getUserId()) ? Cache::put(Config::get('cache.sms.count.phone') . $request->phone, ++$count, 60) :
                Cache::put(Config::get('cache.sms.count.user_id') . parent::getUserId(), ++$count, 60);
        }

        return array('code' => 1, 'message' => '发送前校验成功');
    }
}
