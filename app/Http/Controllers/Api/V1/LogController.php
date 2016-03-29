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

class LogController extends ApiController
{
    /**
     * 创建日志
     *
     * @param string $type 类型
     * @param string $title 标题
     * @param string $data 数据
     * @return apiReturn
     */
    public function postCreate(Request $request)
    {
        // 验证
        $this->apiValidate($request->all(), [
            'type' => 'required|in:A,D,S,U',
            'title' => 'required',
            'data' => 'required'
        ]);

        // 日志队列
        $ips = $request->ips();
        $ip = $ips[0];
        $ips = implode(',', $ips);
        Queue::push(new AppLog(parent::getAppId(), parent::getUserId(), $request->type, $request->title, $request->data, $request->sql, $ip, $ips));

        return Api::apiReturn(SUCCESS, '记录成功');
    }
}
