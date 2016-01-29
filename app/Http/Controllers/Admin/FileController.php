<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FileController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.file.index');
    }

    public function lists(Request $request)
    {
        // 当前应用用户的id
        $userIdsArray = UserRole::where('app_id', Session::get('current_app_id'))->lists('user_id');

        $fields = array('id', 'username', 'email', 'phone', 'created_at', 'updated_at');
        $searchFields = array('username', 'email', 'phone');

        $data = User::whereIn('id', $userIdsArray)
            ->with(['roles' => function($query) {
                $query->where('roles.app_id', Session::get('current_app_id'));
            }])
            ->whereDataTables($request, $searchFields)
            ->orderByDataTables($request)
            ->skip($request->start)
            ->take($request->length)
            ->get($fields);
        $draw = (int)$request->draw;
        $recordsTotal = User::whereIn('id', $userIdsArray)->count();
        $recordsFiltered = strlen($request->search['value']) ? count($data) : $recordsTotal;

        return Api::dataTablesReturn(compact('draw', 'recordsFiltered', 'recordsTotal', 'data'));
    }
}
