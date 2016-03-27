<?php

namespace App\Services;

use Illuminate\Support\Facades\Lang;

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
        $data = (object)$data;

        return response()->json(compact('code', 'message', 'data'));
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
