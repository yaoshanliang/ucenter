<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Model\AppLog;
use Session;
use Cache;
use Config;

class AppLogController extends Controller
{
    // 列表
    public function getIndex()
    {
        return view('admin.applog.index');
    }

    public function postLists(Request $request)
    {
        $searchFields = array('user_id', 'request_method', 'request_url', 'request_params', 'response_code', 'response_message', 'response_data', 'user_id', 'user_ip', 'user_client', 'user_agent');

        $pre = AppLog::where('app_id', Session::get('current_app_id'))
            ->whereDataTables($request, $searchFields)
            ->orderByDataTables($request)
            ->skip($request->start)
            ->take($request->length);
        $data = $pre->get();
        $count = $pre->count();
        $draw = (int)$request->draw;
        $recordsTotal = AppLog::where('app_id', Session::get('current_app_id'))->count();
        $recordsFiltered = strlen($request->search['value']) ? $count : $recordsTotal;

        return $this->response->array(compact('draw', 'recordsFiltered', 'recordsTotal', 'data'));
    }

    // 详细
    public function getLog(Request $request, $id)
    {
        if (!AppLog::where('app_id', Session::get('current_app_id'))->where('id', $id)->exists()) {
            return $this->response->array(array('code' => 0, 'message' => 'Forbidden'));
        }

        $applog = Applog::find($id);
        return $this->response->array(array('code' => 0, 'message' => '获取日志成功', 'data' => $applog));
    }

}
