<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Services\Api;

use Queue;
use App\Jobs\Email;

class EmailController extends ApiController
{
    /**
     * 发送邮件
     *
     * @param Request $request:
     * @return apiReturn
     */
    public function postEmail(Request $request)
    {
        return Api::apiReturn(SUCCESS, '发送成功', $this->_send($request->email, $request->subject, $request->content));
    }

    /**
     * 发送邮件
     *
     * @param string $email 收件箱, string $subject 主题, string $content 内容
     * @return array
     */
    public function _send($email, $subject, $content)
    {
        $this->apiValidate(compact('email', 'subject', 'content'), [
            'email' => 'required|email',
            'subject' => 'required',
            'content' => 'required'
        ]);

        Queue::push(new Email(parent::getAppId(), parent::getUserId(), $email, $subject, $content));
    }

}
