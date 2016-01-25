<?php namespace App\Http\Controllers\Admin;

use Zizaco\Entrust\EntrustPermission;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Role;
use App\Model\User;
use App\Model\UserRole;
use App\Model\Permission;
use App\Model\RolePermission;
use App\Services\Helper;
use App\Services\Api;
use Session;
use Auth;
use Redirect;
class RoleController extends Controller
{
    public function index(Request $request)
    {
		return view('admin.role.index');
	}

    public function lists(Request $request)
    {
		$fields = array('id', 'name', 'title', 'description', 'created_at', 'updated_at');
        $searchFields = array('name', 'title', 'description');

        $data = Role::where('app_id', Session::get('current_app_id'))
            ->whereDataTables($request, $searchFields)
            ->orderByDataTables($request)
			->skip($request->start)
			->take($request->length)
			->get($fields)
            ->toArray();
        $draw = (int)$request->draw;
		$recordsTotal = Role::where('app_id', Session::get('current_app_id'))->count();
		$recordsFiltered = strlen($request->search['value']) ? count($data) : $recordsTotal;

        return Api::dataTablesReturn(compact('draw', 'recordsFiltered', 'recordsTotal', 'data'));
    }

    // 当前角色的权限分组
    public function permission($id)
    {
		return view('admin.role.permission')->with(array('role_id' => $id));
    }

    public function permissionLists(Request $request, $id)
    {
		$fields = array('id', 'name', 'title', 'description', 'created_at', 'updated_at');
        $searchFields = array('name', 'title', 'description');

        $data = Permission::where('app_id', Session::get('current_app_id'))->where('group_id', 0)
            ->whereDataTables($request, $searchFields)
            ->orderByDataTables($request)
			->skip($request->start)
			->take($request->length)
			->get($fields)
            ->toArray();
        $draw = (int)$request->draw;
		$recordsTotal = Permission::where('app_id', Session::get('current_app_id'))->where('group_id', 0)->count();
		$recordsFiltered = strlen($request->search['value']) ? count($data) : $recordsTotal;

        return Api::dataTablesReturn(compact('draw', 'recordsFiltered', 'recordsTotal', 'data'));
    }

    // 获取当前权限分组里的权限
    public function permissionGroup(Request $request, $role_id, $permission_id)
    {
        $permissions = Permission::where('group_id', $permission_id)->get(array('id', 'name', 'title', 'description'))->toArray();

        $role_permissions = DB::table('role_permission')->where('role_id', $role_id)->lists('permission_id');
        foreach($permissions as &$v) {
            if (in_array($v['id'], $role_permissions)) {
                $v['checked'] = 1;
            } else {
                $v['checked'] = 0;
            }
        }

        return Api::jsonReturn(1, '获取权限成功', $permissions);
    }

    // 当前角色已拥有权限列表
    public function permissionSelected($id)
    {
		return view('admin.role.permissionSelected')->with(array('role_id' => $id));
    }

    public function permissionSelectedLists(Request $request, $id)
    {
		$permissionIdsArray = RolePermission::where('role_id', $id)->lists('permission_id');

		$fields = array('id', 'group_id', 'name', 'title', 'description', 'created_at', 'updated_at');
        $searchFields = array('name', 'title', 'description');

        $data = Permission::whereIn('id', $permissionIdsArray)
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
		$recordsTotal = Permission::where('app_id', Session::get('current_app_id'))->where('group_id', 0)->count();
		$recordsFiltered = strlen($request->search['value']) ? count($data) : $recordsTotal;

        return Api::dataTablesReturn(compact('draw', 'recordsFiltered', 'recordsTotal', 'data'));
    }

    public function create()
    {
 		return view('admin.role.create');
 	}

    public function store(Request $request)
    {
        $role = Role::create(array(
            'app_id' => Session::get('current_app_id'),
            'name' => $request->name,
 			'title' => $request->title,
 			'description' => $request->description,
 		));
 		if ($role) {
 			session()->flash('success_message', '角色添加成功');
 			return redirect('/admin/role/app');
 		} else {
 			return redirect()->back()->withInput()->withErrors('保存失败！');
 		}
 	}

 	public function show($id)
 	{
 		//
 	}

 	public function edit($id)
	{
		return view('admin.role.edit')->withRole(Role::find($id));
	}

	public function update(Request $request, $id)
	{
        $role = Role::where('id', $id)->update(array(
            'app_id' => Session::get('current_app_id'),
            'name' => $request->name,
			'title' => $request->title,
			'description' => $request->description,
		));
		if ($role) {
			session()->flash('success_message', '角色修改成功');
			return Redirect::to('/admin/role/app');
		} else {
			return Redirect::back()->withInput()->withErrors('保存失败！');
		}
	}

    public function selectOrUnselectPermission(Request $request, $id, $permission_id)
    {
        if ($request->type == 'select') {
            $rs = DB::table('role_permission')->insert(array(
                'role_id' => $id,
                'permission_id' => $permission_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ));
            $type = '选中权限';
        } else {
            $rs = DB::table('role_permission')->where('role_id', $id)->where('permission_id', $permission_id)->delete();
            $type = '移除权限';
        }

        return empty($rs) ? Api::jsonReturn(0, $type . '失败') : Api::jsonReturn(1, $type . '成功');
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
			// Auth::user()->can('delete-all-app');
			$result = Role::whereIn('id', $ids)->delete();

            // DB::table('oauth_clients')->where('id', $id)->delete();
			DB::commit();
			return Api::jsonpReturn(1, '删除成功', array('deleted_num' => $result));
		} catch (Exception $e) {
			DB::rollBack();
			throw $e;
			return Api::jsonpReturn(0, '删除失败', array('deleted_num' => 0));
		}
	}
}
