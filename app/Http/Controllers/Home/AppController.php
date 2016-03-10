<?php
namespace App\Http\Controllers\Home;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Session;
use DB;
use Cache;
use Config;
use App\Model\App;
use App\Model\UserRole;

class AppController extends Controller
{
    // 接入应用列表
    public function getIndex()
    {
        return view('home.app.index');
    }

    public function postLists(Request $request)
    {
        $fields = array('id', 'name', 'title', 'home_url');
        $searchFields = array('name', 'title');

        $apps = Session::get('apps');
        $roles = Session::get('roles');
        foreach ($apps as $v) {
            $appIdsArray[] = $v['id'];
        }

        $data = App::whereIn('id', $appIdsArray)
            ->whereDataTables($request, $searchFields)
            ->orderByDataTables($request)
            ->skip($request->start)
            ->take($request->length)
            ->get($fields)
            ->toArray();
        foreach ($data as &$value) {
            foreach ($roles[$value['id']] as $v) {
                $value['roles'][] = array_merge($v, UserRole::where('app_id', $value['id'])
                    ->where('user_id', Auth::id())
                    ->where('role_id', $v['id'])
                    ->first(array('created_at'))
                    ->toArray());
            }
        }
        $draw = (int)$request->draw;
        $recordsTotal = App::whereIn('id', $appIdsArray)->count();
        $recordsFiltered = strlen($request->search['value']) ? count($data) : $recordsTotal;

        return $this->response->array(compact('draw', 'recordsFiltered', 'recordsTotal', 'data'));
    }

    // 应用总库列表
    public function getAll()
    {
        return view('home.app.all');
    }

    public function postAlllists(Request $request)
    {
        $fields = array('id', 'name', 'title', 'home_url', 'created_at');
        $searchFields = array('name', 'title');

        $data = App::whereDataTables($request, $searchFields)
            ->orderByDataTables($request)
            ->skip($request->start)
            ->take($request->length)
            ->get($fields);
        foreach ($data as &$v) {
            if (UserRole::where('app_id', $v->id)->where('user_id', Auth::id())->exists()) {
                $v->status = 1;
            } else {
                $v->status = 0;
            }
        }
        $draw = (int)$request->draw;
        $recordsTotal = App::count();
        $recordsFiltered = strlen($request->search['value']) ? count($data) : $recordsTotal;

        return $this->response->array(compact('draw', 'recordsFiltered', 'recordsTotal', 'data'));
    }

    // 申请接入
    public function postAccess(Request $request)
    {
        if (AppAccess::where('app_id', $request->app_id)->where('user_id', Auth::id())->where('type', 'access')->where('operator_id', '')->exists()) {
            return $this->response->array(array('code' => 0, 'message' => '已申请'));
        }
        $appAccess = AppAccess::create(array(
            'app_id' => $request->app_id,
            'user_id' => Auth::id(),
            'type' => 'access',
            'title' => $request->title,
            'description' => $request->description
        ));

        if ($appAccess) {
            return $this->response->array(array('code' => 1, 'message' => '申请接入成功，待审核'));
        } else {
            return $this->response->array(array('code' => 0, 'message' => '申请失败'));
        }
    }

    // 申请退出
    public function deleteAccess(Request $request)
    {
        if (AppAccess::where('app_id', $request->app_id)->where('user_id', Auth::id())->where('type', 'exit')->where('operator_id', '')->exists()) {
            return $this->response->array(array('code' => 0, 'message' => '已申请'));
        }
        $appAccess = AppAccess::create(array(
            'app_id' => $request->app_id,
            'user_id' => Auth::id(),
            'type' => 'exit',
            'title' => $request->title,
            'description' => $request->description
        ));

        if ($appAccess) {
            return $this->response->array(array('code' => 1, 'message' => '申请取消接入成功，待审核'));
        } else {
            return $this->response->array(array('code' => 0, 'message' => '申请失败'));
        }
    }

    // 切换应用
    public function putCurrentApp(Request $request)
    {
        $app = Cache::get(Config::get('cache.apps') . $request->app_id);
		Session::put('current_app', $app);
		Session::put('current_app_title', $app['title']);
		Session::put('current_app_id', $app['id']);

        $roles = Session::get('roles');
        $role = reset($roles[$app['id']]);
		Session::put('current_role', $role);
		Session::put('current_role_title', $role['title']);
		Session::put('current_role_id', $role['id']);

        return $this->response->array(array('code' => 1, 'message' => '切换应用成功'));
    }

    // 切换角色
    public function putCurrentRole(Request $request)
    {
        $oldRole = Session::get('current_role');
        $role = Cache::get(Config::get('cache.roles') . $request->role_id);
		Session::put('current_role', $role);
		Session::put('current_role_title', $role['title']);
		Session::put('current_role_id', $role['id']);

        // temp solution
        if (!in_array($oldRole['name'], array('developer', 'admin')) && in_array($role['name'], array('developer', 'admin'))) {
            return $this->response->array(array('code' => 1, 'message' => '切换角色成功', 'data' => array('redirect' => '/admin/index')));
        }

        return $this->response->array(array('code' => 1, 'message' => '切换角色成功', 'data' => array('redirect' => '')));
    }
}
