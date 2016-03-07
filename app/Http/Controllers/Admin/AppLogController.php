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
        $fields = array('id', 'user_id', 'type', 'title', 'data', 'sql', 'ip', 'pushed_at', 'created_at');
        $searchFields = array('user_id', 'type', 'title');

        $data = AppLog::where('app_id', Session::get('current_app_id'))
            ->whereDataTables($request, $searchFields)
            ->orderByDataTables($request)
            ->skip($request->start)
            ->take($request->length)
            ->get($fields);
        foreach ($data as &$v) {
            $user = Cache::get(Config::get('cache.users') . $v->user_id);
            $v->username = $user['username'];
        }
        $draw = (int)$request->draw;
        $recordsTotal = AppLog::where('app_id', Session::get('current_app_id'))->count();
        $recordsFiltered = strlen($request->search['value']) ? count($data) : $recordsTotal;

        return $this->response->array(compact('draw', 'recordsFiltered', 'recordsTotal', 'data'));
    }

    // 详细
    public function getShow(Request $request, $id)
    {
        if (!AppLog::where('app_id', Session::get('current_app_id'))->where('id', $id)->exists()) {
            return $this->response->array(array('code' => 0, 'message' => 'Forbidden'));
        }

        $applog = Applog::find($id);
        $user = Cache::get(Config::get('cache.users') . $applog->user_id);
        return view('admin.applog.show')->with(compact('applog', 'user'));
    }

}
