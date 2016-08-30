<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\Api;

use Queue;
use App\Model\AppLog;

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
    public function postLog(Request $request)
    {
        // 验证
        $this->apiValidate($request->all(), [
        ]);

        AppLog::create([
            'app_id' => parent::getAppId(),
            'user_id' => "$request->user_id",
            'request_method' => "$request->request_method",
            'request_url' => "$request->request_url",
            'request_params' => "$request->request_params",
            'response_code' => "$request->response_code",
            'response_message' => "$request->response_message",
            'response_data' => "$request->response_data",
            'user_ip' => "$request->user_ip",
            'user_client' => "$request->user_client",
            'user_agent' => "$request->user_agent",
            'server_ip' => $request->ip(),
            'request_at' => "$request->request_at",
            'pushed_at' => "$request->pushed_at",
            'poped_at' => "$request->poped_at",
            'created_at' => $this->getMillisecond(),
            'request_time' => date('Y-m-d H:i:s', (int)$request->request_at),
        ]);

        return Api::apiReturn(SUCCESS, '记录成功');
    }

    public function getMillisecond() {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%f', (floatval($s1) + floatval($s2)));
    }

}
