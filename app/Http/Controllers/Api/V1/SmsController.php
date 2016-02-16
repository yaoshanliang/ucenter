<?php
namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use Cache;
use Queue;
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
        if ( 11 != strlen($request->get('phone'))) {
            return $this->response->array(array('code' => 0, 'message' => '手机号不合法'));
        }

        Sms::enable('Luosimao', '80 backup');
        // dd(PhpSms::queue());
        // PhpSms::voice(1234)->to('18896581232')->send();exit;
        PhpSms::make()->to('18896581232')->content('【签名】这是短信内容...')->send();
        // PhpSms::make()->to('18896581232')->template('Luosimao', 'xxx')->data(['12345', 5])->send();
        return $this->response->array(compact('code', 'message', 'data'));
    }
}
