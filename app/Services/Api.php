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
	public static function json_return($code, $msg = '', $data = array()) {
		if(empty($msg)) {
			$msg = Lang::has('api.' . $code) ? trans('api.' . $code) : '';
		}
		if(empty($data)) {
			return response()->json(array('code' => $code, 'msg' => $msg));
		} else {
			return response()->json(array('code' => $code, 'msg' => $msg, 'data' => $data));
		}
	}
}
