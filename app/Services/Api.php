<?php

namespace App\Services;

use Illuminate\Support\Facades\Lang;

define('SUCCESS', 1);
define('ERROR', 0);
define('BADREQUEST', 1000);
define('UNAUTHORIZED', 1001);
define('FORBIDDEN', 1003);

// 状态码
define('STATUSCODE', [
    'OK' => 200,
    'BADREQUEST' => 400,
    'UNAUTHORIZED' => 401,
    'FORBIDDEN' => 403,
    'INTERNALSERVERERROR' => 500,
]);

class Api
{
    /**
     * apiReturn
     *
     * @param int $code 代码
     * @param string $message 消息
     * @param array $data 数据
     *
     * @return json
     */
    public static function apiReturn($code, $message = '', $data = [])
    {
        switch ($code) {
            case SUCCESS:
                $statusCode = STATUSCODE['OK'];
                break;

            case ERROR:
                $statusCode = STATUSCODE['BADREQUEST'];
                break;

            case UNAUTHORIZED:
                $statusCode = STATUSCODE['UNAUTHORIZED'];
                break;

            case FORBIDDEN:
                $statusCode = STATUSCODE['FORBIDDEN'];
                break;

            default:
                $statusCode = STATUSCODE['INTERNALSERVERERROR'];
        }

        $data = (object)$data;

        return response(compact('code', 'message', 'data'), $statusCode)->header('Content-Type', 'application/json');
    }

    /**
     * dataTablesReturn
     *
     * @param array $data 数据
     *
     * @return json
     */
    public static function dataTablesReturn($data)
    {
        return response()->json($data);
    }
}
