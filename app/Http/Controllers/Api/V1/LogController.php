<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use Cache;
use Config;
use Queue;
use Validator;
use App\Jobs\AppLog;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

class LogController extends ApiController
{
    public function postCreate(Request $request)
    {
        // 验证
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:A,D,S,U',
            'title' => 'required',
            'data' => 'required'
        ]);

        // 返回验证失败信息
        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return $this->response->array(array('code' => 0, 'message' => $message));
        }

        // 日志队列
        $ips = $request->ips();
        $ip = $ips[0];
        $ips = implode(',', $ips);
        Queue::push(new AppLog(parent::$currentAppId, parent::$currentUserId, $request->type, $request->title, $request->data, $request->sql, $ip, $ips));

        return $this->response->array(array('code' => 1, 'message' => '记录成功'));
    }
}
