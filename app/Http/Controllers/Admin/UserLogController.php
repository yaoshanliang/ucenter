<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Model\UserLog;
use Session;
use App\Services\Api;

class UserLogController extends Controller
{
	public function index()
	{
		return view('admin.userlog.index');
	}

    public function lists(Request $request)
    {
		$fields = array('id', 'user_id', 'type', 'title', 'data', 'sql', 'ip', 'pushed_at', 'created_at');
        $searchFields = array('user_id', 'type', 'title');

        $data = UserLog::where('app_id', Session::get('current_app_id'))
            ->whereDataTables($request, $searchFields)
            ->orderByDataTables($request)
			->skip($request->start)
			->take($request->length)
			->get($fields)
            ->toArray();
        $draw = (int)$request->draw;
		$recordsTotal = UserLog::where('app_id', Session::get('current_app_id'))->count();
		$recordsFiltered = strlen($request->search['value']) ? count($data) : $recordsTotal;

        return Api::dataTablesReturn(compact('draw', 'recordsFiltered', 'recordsTotal', 'data'));
    }
}
