<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Model\AppLog;
use Session;

class AppLogController extends Controller
{
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
        $draw = (int)$request->draw;
		$recordsTotal = AppLog::where('app_id', Session::get('current_app_id'))->count();
		$recordsFiltered = strlen($request->search['value']) ? count($data) : $recordsTotal;

        return $this->response->array(compact('draw', 'recordsFiltered', 'recordsTotal', 'data'));
    }
}
