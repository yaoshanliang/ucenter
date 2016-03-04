<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Model\User;
use App\Model\Role;
use App\Model\UserRole;
use App\Model\App;
use App\Model\UserFields;

use Auth;
use Cache;
use Queue;
use Session;
use Config;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    public function getIndex(Request $request)
    {
        return view('admin.user.index');
    }

    public function postLists(Request $request)
    {
        // 当前应用用户的id
        $userIdsArray = UserRole::where('app_id', Session::get('current_app_id'))->lists('user_id');

        $fields = array('id', 'username', 'email', 'phone', 'created_at', 'updated_at');
        $searchFields = array('username', 'email', 'phone');

        $data = User::whereIn('id', $userIdsArray)
            ->with(['appRoles' => function($query) {
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

        return $this->response->array(compact('draw', 'recordsFiltered', 'recordsTotal', 'data'));
    }

    // 用户总库
    public function getAll(Request $request)
    {
        return view('admin.user.all');
    }

    public function postAlllists(Request $request)
    {
        $fields = array('id', 'username', 'email', 'phone', 'created_at', 'updated_at');
        $searchFields = array('username', 'email', 'phone');

        $data = User::whereDataTables($request, $searchFields)
            ->orderByDataTables($request)
            ->skip($request->start)
            ->take($request->length)
            ->get($fields);

        $roles = UserRole::where('app_id', Session::get('current_app_id'))->lists('user_id')->toArray();
        foreach ($data as $k => $v) {
            if (in_array($v['id'], $roles)) {
                $v['status'] = 1;
            } else {
                $v['status'] = 0;
            }
        }

        $draw = (int)$request->draw;
        $recordsTotal = User::count();
        $recordsFiltered = strlen($request->search['value']) ? count($data) : $recordsTotal;

        return $this->response->array(compact('draw', 'recordsFiltered', 'recordsTotal', 'data'));
    }

    // 用户信息字段
    public function getFields(Request $request)
    {
        return view('admin.user.fields');
    }

    public function postFieldslists(Request $request)
    {
        $fields = array('id', 'name', 'title', 'type', 'description', 'created_at', 'updated_at');
        $searchFields = array('name', 'title', 'type');

        $data = UserFields::whereDataTables($request, $searchFields)
            ->orderByDataTables($request)
            ->skip($request->start)
            ->take($request->length)
            ->get($fields);

        $draw = (int)$request->draw;
        $recordsTotal = UserFields::count();
        $recordsFiltered = strlen($request->search['value']) ? count($data) : $recordsTotal;

        return $this->response->array(compact('draw', 'recordsFiltered', 'recordsTotal', 'data'));
    }

    // 邀请加入
    public function getInvite()
    {
        $roles = Role::where('app_id', Session::get('current_app_id'))->where('name', '<>', 'developer')->get(array('id', 'title'));
        return view('admin.user.invite')->withRoles($roles);
    }

    public function postInvite(Request $request)
    {
        $this->validate($request, array(
            'email' => 'required|email|unique:users',
            'username' => 'required|min:3|unique:users',
            'role_id' => 'required',
        ));

        $token = hash_hmac('sha256', str_random(40), env('APP_KEY'));

        // 写入用户库
        $user = User::create(array('username' => $request->username, 'email' => $request->email));

        // 写入密码重置
        $password_reset = DB::table('password_resets')->insert(array(
            'email' => $request->email,
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s')
        ));

        // 写入用户角色
        $user_role = UserRole::create(array(
            'app_id' => Session::get('current_app_id'),
            'user_id' => $user->id,
            'role_id' => $request->role_id,
        ));

        // 发送邀请邮件
        $mail = Queue::push(new SendEmail('invite', '邀请入驻用户中心', $token, $request->email));

        if ($user && $password_reset && $user_role && $mail) {
            session()->flash('success_message', '用户邀请成功');
            return Redirect::to('/admin/user/invite');
        } else {
            return Redirect::back()->withInput()->withErrors('保存失败！');
        }
    }

    // 显示用户详细
    public function getShow(UserRequest $request, $id)
    {
        if (!UserRole::where('user_id', $id)->where('app_id', Session::get('current_app_id'))->exists()) {
            return $this->response->array(array('code' => 0, 'message' => 'Forbidden'));
        }
        $user = Cache::get(Config::get('cache.users') . $id);

        return view('admin.user.show')->withUser($user);
    }

    // 从当前应用中移出用户
    public function postRemove(Request $request)
    {
        DB::beginTransaction();
        try {
            $ids = $request->ids;
            $app = App::find(Session::get('current_app_id'))->toArray();
            if (in_array($app['user_id'], $ids)) {
                return Api::jsonReturn(0, '移除失败, 不允许移除创建者');
            }
            if (in_array(Auth::id(), $ids)) {
                return Api::jsonReturn(0, '移除失败, 不允许移除自己');
            }
            $result = UserRole::where('app_id', Session::get('current_app_id'))->whereIn('user_id', $ids)->delete();

            DB::commit();

            return $this->response->array(array('code' => 1, 'message' => '移除成功'));
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;

            return $this->response->array(array('code' => 0, 'message' => '移除失败'));
        }
    }

    // 当前用户的角色
    public function getRoles($user_id)
    {
        $roles = Role::where('app_id', Session::get('current_app_id'))
            ->where('name', '<>', 'developer')
            ->get(array('id', 'name', 'title', 'description', 'updated_at'))
            ->toArray();
        $userRoles = UserRole::where('app_id', Session::get('current_app_id'))->where('user_id', $user_id)->lists('role_id')->toArray();

        foreach ($roles as &$v) {
            if (in_array($v['id'], $userRoles)) {
                $v['checked'] = 1;
            } else {
                $v['checked'] = 0;
            }
        }

        return $this->response->array(array('code' => 1, 'message' => '获取成功', 'data' => $roles));
    }

    // 勾选或取消勾选角色
    public function selectOrUnselectRole(Request $request, $id, $role_id)
    {
        if (Role::where('id', $role_id)->where('name', 'developer')->exists()) {
            return $this->response->array(array('code' => 0, 'message' => '开发者权限限制'));
        }

        if ($request->type == 'select') {
            $rs = UserRole::create(array(
                'user_id' => $id,
                'role_id' => $role_id,
                'app_id' => Session::get('current_app_id')
            ));
            $type = '选中角色';
        } else {
            $rs = UserRole::where('user_id', $id)
                ->where('role_id', $role_id)
                ->where('app_id', Session::get('current_app_id'))
                ->delete();
            $type = '移除角色';
        }

        return empty($rs) ? $this->response->array(array('code' => 0, 'message' => $type . '失败')) :
            $this->response->array(array('code' => 1, 'message' => $type . '成功'));
    }
}
