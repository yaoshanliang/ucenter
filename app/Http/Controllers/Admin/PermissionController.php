<?php
namespace App\Http\Controllers\Admin;

use Zizaco\Entrust\EntrustPermission;
use App\Http\Requests;
use App\Http\Requests\PermissionRequest;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Role;
use App\Model\User;
use App\Model\Permission;
use App\Services\Api;
use Session;
use Redirect;
class PermissionController extends Controller
{
    public function index(Request $request)
    {
		return view('admin.permission.index');
	}

    public function lists(Request $request)
    {
		$fields = array('id', 'group_id', 'name', 'title', 'description', 'created_at', 'updated_at');
        $searchFields = array('name', 'title', 'description');

        $data = Permission::where('app_id', Session::get('current_app_id'))
            ->where('group_id', '<>', 0)
            ->whereDataTables($request, $searchFields)
            ->orderByDataTables($request)
			->skip($request->start)
			->take($request->length)
			->get($fields);
        foreach($data as $v) {
            $v['group_name'] = $v->group->title;
            unset($v['group']);
        }
        $draw = (int)$request->draw;
		$recordsTotal = Permission::where('app_id', Session::get('current_app_id'))->where('group_id', '<>', 0)->count();
		$recordsFiltered = strlen($request->search['value']) ? count($data) : $recordsTotal;

        return Api::dataTablesReturn(compact('draw', 'recordsFiltered', 'recordsTotal', 'data'));
    }

    public function create()
    {
        $groups = Permission::where('app_id', Session::get('current_app_id'))->where('group_id', 0)->get(array('id', 'title'));
		return view('admin.permission.create')->withGroups($groups);
    }

    public function createGroup()
    {
		return view('admin.permission.createGroup');
	}

    public function group()
    {
		return view('admin.permission.group');
    }

    public function groupLists(Request $request)
    {
		$fields = array('id', 'name', 'title', 'description', 'created_at', 'updated_at');
        $searchFields = array('name', 'title', 'description');

        $data = Permission::where('app_id', Session::get('current_app_id'))
            ->where('group_id', 0)
            ->whereDataTables($request, $searchFields)
            ->orderByDataTables($request)
			->skip($request->start)
			->take($request->length)
			->get($fields);
        $draw = (int)$request->draw;
		$recordsTotal = Permission::where('app_id', Session::get('current_app_id'))->where('group_id', 0)->count();
		$recordsFiltered = strlen($request->search['value']) ? count($data) : $recordsTotal;

        return Api::dataTablesReturn(compact('draw', 'recordsFiltered', 'recordsTotal', 'data'));
    }

    public function store(Request $request)
    {
        $permission = Permission::create(array(
            'app_id' => Session::get('current_app_id'),
            'group_id' => is_null($request->group_id) ? 0 : $request->group_id,
            'name' => $request->name,
			'title' => $request->title,
			'description' => $request->description,
		));
		if ($permission) {
			session()->flash('success_message', '添加成功');
            if ($request->group_id) {
			    return redirect('/admin/permission');
            } else {
			    return redirect('/admin/permission/group');
            }
		} else {
			return redirect()->back()->withInput()->withErrors('保存失败！');
		}
		//
	}

	public function show($id)
	{
		//
	}

	public function edit(PermissionRequest $request, $id)
	{
		return view('admin.permission.edit')->withPermission(Permission::find($id));
	}

	public function update(PermissionRequest $request, $id)
	{
        $permission = Permission::where('id', $id)->update(array(
            'app_id' => Session::get('current_app_id'),
            'name' => $request->name,
			'title' => $request->title,
			'description' => $request->description,
		));
		if ($permission) {
			session()->flash('success_message', '权限修改成功');
            if ($request->group_id) {
			    return redirect('/admin/permission');
            } else {
			    return redirect('/admin/permission/group');
            }
		} else {
			return Redirect::back()->withInput()->withErrors('保存失败！');
		}
		//
	}

	public function destroy($id)
	{
		//
	}

	public function delete()
	{
		DB::beginTransaction();
		try {
			$ids = $_POST['ids'];
            $permissions = Permission::whereIn('id', $ids)->lists('app_id')->toArray();
            if (!in_array(Session::get('current_app_id'), $permissions)) {
                return Api::jsonReturn(0, '不允许删除');
            }
            $role = Role::where('app_id', Session::get('current_app_id'))->where('name', 'developer')->first()->toArray();
			$result = Permission::whereIn('id', $ids)->delete();
            Permission::whereIn('group_id', $ids)->delete();

			DB::commit();
			return Api::jsonReturn(1, '删除成功', array('deleted_num' => $result));
		} catch (Exception $e) {
			DB::rollBack();
			throw $e;
			return Api::jsonReturn(0, '删除失败', array('deleted_num' => 0));
		}
	}
}
