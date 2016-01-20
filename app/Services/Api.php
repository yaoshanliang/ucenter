<?php namespace App\Services;
use Illuminate\Support\Facades\Lang;
class Api {
	/**
	 * 返回jsonp格式数据
	 *
	 * @param  string $status_code, string $status_msg, array $status_data
	 * @return jsonp格式字符串
	 */
	public static function jsonp_return($status_code, $status_msg, $status_data = array()) {
		$data['status_code'] = $status_code;
		$data['status_msg'] = $status_msg;
		$data['status_data'] = $status_data;
		$jsonp = preg_match('/^[$A-Z_][0-9A-Z_$]*$/i', $_GET['callback']) ? $_GET['callback'] : false;
		if($jsonp) {
		    echo $jsonp . '(' . json_encode($data, JSON_UNESCAPED_UNICODE) . ');';
		}
	}

	/**
	 * 返回json格式数据
	 *
	 * @param  int $code, string $msg, array $data
	 * @return jsonp格式字符串
	 */
	public static function json_return($code, $message = '', $data = array()) {
		if(empty($message)) {
			$message = Lang::has('api.' . $code) ? trans('api.' . $code) : '';
		}
		if(empty($data)) {
			return response()->json(array('code' => $code, 'message' => $message));
			header('Content-Type: application/json');
			return json_encode(array('code' => $code, 'message' => $message));
		} else {
			return response()->json(array('code' => $code, 'message' => $message, 'data' => $data));
			header('Content-Type: application/json');
			return json_encode(array('code' => $code, 'message' => $message, 'data' => $data));
		}
	}

    public static function apiReturn() {
        return response()->json(compact('code', 'message', 'data'));
    }
}
