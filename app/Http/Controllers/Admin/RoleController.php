<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Requests\RoleRequest;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Role;
use App\Model\User;
use App\Model\UserRole;
use App\Model\Permission;
use App\Model\RolePermission;
use Session;
use Auth;
use Config;
use Cache;

class RoleController extends Controller
{
    // 角色列表
    public function getIndex(Request $request)
    {
        return view('admin.role.index');
    }

    public function postLists(Request $request)
    {
        $fields = array('id', 'name', 'title', 'description', 'created_at', 'updated_at');
        $searchFields = array('name', 'title', 'description');

        $data = Role::where('app_id', Session::get('current_app_id'))
            ->where('name', '<>', 'developer')
            ->whereDataTables($request, $searchFields)
            ->orderByDataTables($request)
            ->skip($request->start)
            ->take($request->length)
            ->get($fields);
        $draw = (int)$request->draw;
        $recordsTotal = Role::where('app_id', Session::get('current_app_id'))->where('name', '<>', 'developer')->count();
        $recordsFiltered = strlen($request->search['value']) ? count($data) : $recordsTotal;

        return $this->response->array(compact('draw', 'recordsFiltered', 'recordsTotal', 'data'));
    }

    // 当前角色的权限分组
    public function getPermission(RoleRequest $request, $id)
    {
        return view('admin.role.permission')->withRole(Role::find($id));
    }

    public function postPermissionlists(RoleRequest $request, $id)
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

        return $this->response->array(compact('draw', 'recordsFiltered', 'recordsTotal', 'data'));
    }

    // 获取当前权限分组里的权限
    public function getPermissiongroup(RoleRequest $request, $role_id, $permission_id)
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

        return $this->response->array(array('code' => 0, 'message' => '获取权限成功', 'data' => $permissions));
    }

    // 当前角色已拥有权限列表
    public function getPermissionselected(RoleRequest $request, $id)
    {
        return view('admin.role.permissionSelected')->withRole(Role::find($id));
    }

    public function postPermissionselectedlists(RoleRequest $request, $id)
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
        $recordsTotal = RolePermission::where('role_id', $id)->count();
        $recordsFiltered = strlen($request->search['value']) ? count($data) : $recordsTotal;

        return $this->response->array(compact('draw', 'recordsFiltered', 'recordsTotal', 'data'));
    }

    // 新增角色
    public function getCreate()
    {
        return view('admin.role.create');
    }

    public function postCreate(Request $request)
    {
        $this->validate($request, array(
            'name' => 'required|unique:roles,name,,,app_id,' . Session::get('current_app_id'),// Usage see:/vendor/laravel/framework/src/Illuminate/Validation/DatabasePresenceVerifier.php
            'title' => 'required',
        ));

        $role = Role::create(array(
            'app_id' => Session::get('current_app_id'),
            'name' => $request->name,
            'title' => $request->title,
            'description' => $request->description,
        ));

        // 更新cache
        $this->cacheRoles($role->id);

        // 日志
        $this->log('A', '新增角色', "id: $role->id; title: $request->title;");

        if ($role) {
            session()->flash('success_message', '角色添加成功');
            return redirect('/admin/role');
        } else {
            return redirect()->back()->withInput()->withErrors('保存失败！');
        }
    }

    // 编辑角色
    public function getEdit(RoleRequest $request, $id)
    {
        return view('admin.role.edit')->withRole(Role::find($id));
    }

    public function putEdit(RoleRequest $request, $id)
    {
        $this->validate($request, array(
            'name' => 'required|unique:roles,name,' . $id . ',id,app_id,' . Session::get('current_app_id'),
            'title' => 'required',
        ));

        $role = Role::where('id', $id)->update(array(
            'app_id' => Session::get('current_app_id'),
            'name' => $request->name,
            'title' => $request->title,
            'description' => $request->description,
        ));
        if ($role) {

            // 更新cache
            $this->cacheRoles($id);

            // 写入日志
            $field = array('name', 'title', 'description');
            foreach ($field as $v) {
                $old[$v] = $request->{'old_' . $v};
                $new[$v] = $request->{$v};
            }
            $diff = array_diff_assoc($old, $new);
            $data = 'id: ' . $id . ', ';
            foreach ($diff as $k => &$v) {
                $data .= $k . ': ' . $v . ' => ' . $new[$k] . '; ';
            }
            $this->log('U', '修改角色', $data);

            session()->flash('success_message', '角色修改成功');
            return redirect('/admin/role');
        } else {
            return redirect()->back()->withInput()->withErrors('保存失败！');
        }
    }

    // 选中或取消选中权限
    public function putPermission(RoleRequest $request)
    {
        $roleId = $request->role_id;
        $permissionId = $request->permission_id;

        $role = Cache::get(Config::get('cache.roles') . $roleId);
        $permission = Cache::get(Config::get('cache.permissions') . $permissionId);

        if (RolePermission::where('role_id', $roleId)->where('permission_id', $permissionId)->exists()) {
            $rs = RolePermission::where('role_id', $roleId)->where('permission_id', $permissionId)->delete();
            $type = '移除权限';

            $this->log('D', '角色' . $type, "role_id: $roleId; title: {$role['title']}; permission_id: $permissionId; title: {$permission['title']}");
        } else {
            $rs = RolePermission::create(array(
                'role_id' => $roleId,
                'permission_id' => $permissionId,
            ));
            $type = '选中权限';

            $this->log('A', '角色' . $type, "role_id: $roleId; title: {$role['title']}; permission_id: $permissionId; title: {$permission['title']}");
        }

        return empty($rs) ? $this->response->array(array('code' => 0, 'message' => $type . '失败')) :
            $this->response->array(array('code' => 0, 'message' => $type . '成功'));
    }

    // 删除
    public function deleteDelete(Request $request)
    {
        DB::beginTransaction();
        try {
            $ids = $request->ids;
            $roles = Role::whereIn('id', $ids)->lists('app_id')->toArray();
            if (!in_array(Session::get('current_app_id'), $roles)) {
                return $this->response->array(array('code' => 0, 'message' => '不允许删除'));
            }
            $role = Role::where('app_id', Session::get('current_app_id'))->where('name', 'developer')->first()->toArray();
            if (in_array($role['id'], $ids)) {
                return $this->response->array(array('code' => 0, 'message' => '开发者不允许删除'));
            }
            $result = Role::whereIn('id', $ids)->delete();

            DB::commit();

            // 清除cache
            $data = '';
            foreach ($ids as $v) {
                $role = Cache::get(Config::get('cache.roles') . $v);
                $data .= 'id: ' . $v . ', title: ' . $role['title'] . '; ';

                Cache::forget(Config::get('cache.apps') . $v);
            }

            // 日志
            $this->log('D', '删除角色', $data);

            return $this->response->array(array('code' => 0, 'message' => '删除成功'));
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;

            return $this->response->array(array('code' => 1, 'message' => '删除失败'));
        }
    }
}
