<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Requests\PermissionRequest;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Role;
use App\Model\User;
use App\Model\Permission;
use Session;

class PermissionController extends Controller
{
    // 权限列表
    public function getIndex(Request $request)
    {
		return view('admin.permission.index');
	}

    public function postLists(Request $request)
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

        return $this->response->array(compact('draw', 'recordsFiltered', 'recordsTotal', 'data'));
    }

    // 创建角色
    public function getCreate()
    {
        $groups = Permission::where('app_id', Session::get('current_app_id'))->where('group_id', 0)->get(array('id', 'title'));
		return view('admin.permission.create')->withGroups($groups);
    }

    public function postCreate(Request $request)
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
	}

    // 修改权限
	public function getEdit(PermissionRequest $request, $id)
	{
		return view('admin.permission.edit')->withPermission(Permission::find($id));
	}

	public function putEdit(PermissionRequest $request, $id)
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
	}

    // 创建权限分组
    public function getCreategroup()
    {
		return view('admin.permission.createGroup');
	}

    // 显示权限分组
    public function getGroup()
    {
		return view('admin.permission.group');
    }

    public function postGrouplists(Request $request)
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

        return $this->response->array(compact('draw', 'recordsFiltered', 'recordsTotal', 'data'));
    }

    // 删除
	public function deleteDelete(Request $request)
	{
		DB::beginTransaction();
		try {
			$ids = $request->ids;
            $permissions = Permission::whereIn('id', $ids)->lists('app_id')->toArray();
            if (!in_array(Session::get('current_app_id'), $permissions)) {
                return $this->response->array(array('code' => 0, 'message' => '不允许删除'));
            }
            $role = Role::where('app_id', Session::get('current_app_id'))->where('name', 'developer')->first()->toArray();
			$result = Permission::whereIn('id', $ids)->delete();
            Permission::whereIn('group_id', $ids)->delete();

			DB::commit();

            return $this->response->array(array('code' => 1, 'message' => '删除成功'));
		} catch (Exception $e) {
			DB::rollBack();
			throw $e;

            return $this->response->array(array('code' => 0, 'message' => '删除失败'));
		}
	}
}
