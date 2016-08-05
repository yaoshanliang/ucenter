<?php
namespace App\Http\Controllers\Home;

use App\Http\Requests;
use App\Http\Requests\AppRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Session;
use DB;
use Cache;
use Config;
use App\Model\App;
use App\Model\Role;
use App\Model\UserRole;
use App\Model\AppAccess;

class AppController extends Controller
{
    // 创建应用列表
    public function getIndex()
    {
        return view('home.app.index');
    }

    public function postLists(Request $request)
    {
        $fields = array('id', 'name', 'title', 'home_url', 'user_id', 'created_at', 'updated_at');
        $searchFields = array('name', 'title');

        $data = App::where('user_id', Auth::id())
            ->whereDataTables($request, $searchFields)
            ->orderByDataTables($request)
            ->skip($request->start)
            ->take($request->length)
            ->get($fields);
        $draw = (int)$request->draw;
        $recordsTotal = App::where('user_id', Auth::id())->count();
        $recordsFiltered = strlen($request->search['value']) ? count($data) : $recordsTotal;

        return $this->response->array(compact('draw', 'recordsFiltered', 'recordsTotal', 'data'));
    }

    // 接入应用列表
    public function getAccess()
    {
        return view('home.app.access');
    }

    public function postAccesslists(Request $request)
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
            if (AppAccess::where('app_id', $v->id)->where('user_id', Auth::id())->where('type', 'access')->where('handler_id', 0)->exists()){
                $v->status = 'access';
            } elseif (AppAccess::where('app_id', $v->id)->where('user_id', Auth::id())->where('type', 'exit')->where('handler_id', 0)->exists()){
                $v->status = 'exit';
            } else if (UserRole::where('app_id', $v->id)->where('user_id', Auth::id())->exists()) {
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

    // 申请接入、退出
    public function postAccess(Request $request)
    {
        // 禁止退出的应用
        if ('exit' == $request->type) {
            $appRole = DB::table('apps')
                ->where('apps.name', env('DEFAULT_APP'))
                ->join('roles', 'apps.id', '=', 'roles.app_id')
                ->where('roles.name', env('DEFAULT_ROLE'))
                ->select('apps.id as app_id', 'roles.id as role_id')
                ->first();
            if ($appRole->app_id == $request->app_id) {
                return $this->response->array(array('code' => 0, 'message' => '该应用不允许退出'));
            }
        }

        if (AppAccess::where('app_id', $request->app_id)->where('user_id', Auth::id())->where('type', $request->type)->where('handler_id', 0)->exists()) {
            return $this->response->array(array('code' => 0, 'message' => '已申请，请务重复申请'));
        }
        $appAccess = AppAccess::create(array(
            'app_id' => $request->app_id,
            'user_id' => Auth::id(),
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description
        ));

        if ($appAccess) {
            $type = ('access' == $request->type) ? '接入' : '退出';
            $this->log('A', '申请' . $type . '应用', "app_id: $request->app_id, user_id: " . Auth::id());
            return $this->response->array(array('code' => 0, 'message' => '申请成功，待审核'));
        } else {
            return $this->response->array(array('code' => 1, 'message' => '申请失败'));
        }
    }

    // 取消接入、退出
    public function deleteAccess(Request $request)
    {
        if (!AppAccess::where('app_id', $request->app_id)->where('user_id', Auth::id())->where('type', $request->type)->where('handler_id', 0)->exists()) {
            return $this->response->array(array('code' => 0, 'message' => '已处理，不可取消'));
        }
        $appAccess = AppAccess::where('app_id', $request->app_id)->where('user_id', Auth::id())->where('type', $request->type)->where('handler_id', 0)->delete();

        if ($appAccess) {
            $type = ('access' == $request->type) ? '接入' : '退出';
            $this->log('A', '取消申请' . $type . '应用', "app_id: $request->app_id, user_id: " . Auth::id());
            return $this->response->array(array('code' => 0, 'message' => '取消成功'));
        } else {
            return $this->response->array(array('code' => 1, 'message' => '取消失败'));
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

        return $this->response->array(array('code' => 0, 'message' => '切换应用成功'));
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
            return $this->response->array(array('code' => 0, 'message' => '切换角色成功', 'data' => array('redirect' => '/admin/index')));
        }

        return $this->response->array(array('code' => 0, 'message' => '切换角色成功', 'data' => array('redirect' => '')));
    }

    // 切换到管理后台
    public function putAdmin(Request $request)
    {
        $app = Cache::get(Config::get('cache.apps') . $request->app_id);
		Session::put('current_app', $app);
		Session::put('current_app_title', $app['title']);
		Session::put('current_app_id', $app['id']);

        $roles = Session::get('roles');
        foreach ($roles[$app['id']] as $v) {
            if ('developer' == $v['name']) {
                $role = $v;
            }
        }
        if (!isset($role)) {
            return $this->response->array(array('code' => 0, 'message' => '切换失败'));
        }

		Session::put('current_role', $role);
		Session::put('current_role_title', $role['title']);
		Session::put('current_role_id', $role['id']);

        return $this->response->array(array('code' => 0, 'message' => '切换成功', 'data' => array('redirect' => '/admin/index')));
    }

    // 创建应用
    public function getCreate()
    {
        return view('home.app.create');
    }

    public function postCreate(Request $request)
    {
        $this->validate($request, array(
            'title' => 'required',
            'home_url' => 'required|url',
            'login_url' => 'required|url',
        ));

        $request->name = uniqid('UC');
        $request->secret = md5(uniqid(time() . rand(1000, 9999)));

        $app = App::create(array('name' => $request->name,
            'title' => $request->title,
            'description' => $request->description,
            'home_url' => $request->home_url,
            'login_url' => $request->login_url,
            'user_id' => Auth::id()
        ));

        // 接入oauth_clients
        $oauth_client = DB::table('oauth_clients')->insert(array(
            'id' => $request->name,
            'secret' => $request->secret,
            'name' => $request->title,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));

        $oauth_client_endpoints = DB::table('oauth_client_endpoints')->insert(array(
            'client_id' => $request->name,
            'redirect_uri' => $request->login_url,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));

        // 默认开发者角色
        $role = Role::create(array(
            'app_id' => $app->id,
            'name' => 'developer',
            'title' => '开发者',
            'description' => '开发者',
        ));

        $user_role = DB::table('user_role')->insert(array(
            'user_id' => Auth::id(),
            'app_id' => $app->id,
            'role_id' => $role->id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));

        // 勾选访客角色
        if ($request->role) {
            Role::create(array(
                'app_id' => $app->id,
                'name' => 'guest',
                'title' => '访客',
                'description' => '访客'
            ));
        }

        // 更新cache
        $this->cacheApps($app->id);
        $this->cacheRoles($role->id);

        // 更新session
        $this->initRole();

        // 写入日志
        $this->log('A', '新增应用', 'id: ' . $app->id . ', ' . 'title : ' . $request->title);

        if ($app && $oauth_client && $role && $user_role) {
            session()->flash('success_message', '应用添加成功');
            return redirect('/home/app');
        } else {
            return redirect()->back()->withInput()->withErrors('保存失败！');
        }
    }

    public function show($id)
    {
    }

    // 修改应用
    public function getEdit(AppRequest $request, $id)
    {
        $app = App::find($id);
        $client = DB::table('oauth_clients')->find($app->name);
        $app->secret = $client->secret;

        return view('home.app.edit')->with(['app' => $app, 'accessToken' => parent::accessToken()]);
    }

    public function putEdit(AppRequest $request, $id)
    {
        $this->validate($request, array(
            'name' => 'required|unique:apps,name,'.$id.'',
            'title' => 'required',
            'home_url' => 'required|url',
            'login_url' => 'required|url',
            'secret' => 'required'
        ));

        $app = App::where('id', $id)->update(array(
            'name' => $request->name,
            'title' => $request->title,
            'description' => $request->description,
            'home_url' => $request->home_url,
            'login_url' => $request->login_url,
            'user_id' => Auth::id()
        ));

        $oauth_client = DB::table('oauth_clients')->where('id', $request->old_name)->update(array(
            'id' => $request->name,
            'secret' => $request->secret,
            'name' => $request->title,
            'updated_at' => date('Y-m-d H:i:s')
        ));

        $oauth_client_endpoints = DB::table('oauth_client_endpoints')->where('client_id', $request->old_name)->update(array(
            'redirect_uri' => $request->login_url,
            'updated_at' => date('Y-m-d H:i:s')
        ));

        // 更新cache
        $this->cacheApps($id);

        // 更新session
        $this->initRole();

        // 写入日志
        $field = array('name', 'title', 'description', 'home_url', 'login_url', 'secret');
        foreach ($field as $v) {
            $old[$v] = $request->{'old_' . $v};
            $new[$v] = $request->{$v};
        }
        $diff = array_diff_assoc($old, $new);
        $diffData = 'id: ' . $id . ', ';
        foreach ($diff as $k => &$v) {
            $diffData .= $k . ': ' . $v . ' => ' . $new[$k] . '; ';
        }
        $this->log('U', '修改应用', $diffData);

        if ($app && $oauth_client) {
            session()->flash('success_message', '应用修改成功');
            return redirect('/home/app');
        } else {
            return redirect()->back()->withInput()->withErrors('保存失败！');
        }
    }

    // 删除
    public function deleteDelete(Request $request)
    {
        DB::beginTransaction();
        try {
            $ids = $request->ids;
            foreach ($ids as $v) {
                if (Session::get('current_app_id') == $v) {
                    return $this->response->array(array('code' => 0, 'message' => '不允许删除当前应用'));
                }
                if (!App::where('user_id', Auth::id())->where('id', $v)->exists()) {
                    return $this->response->array(array('code' => 0, 'message' => '不允许删除他人应用'));
                }
            }
            $appNames = App::whereIn('id', $ids)->lists('name');
            $result = App::whereIn('id', $ids)->delete();

            DB::table('oauth_clients')->whereIn('id', $appNames)->delete();
            DB::commit();

            // 清除cache
            $appTitles = '';
            foreach ($ids as $v) {
                $app = Cache::get(Config::get('cache.apps') . $v);
                $appTitles .= 'id: ' . $v . ', title: ' . $app['title'] . '; ';

                Cache::forget(Config::get('cache.apps') . $v);
            }
            foreach ($appNames as $v) {
                Cache::forget(Config::get('cache.clients') . $v);
            }

            // 更新session
            $this->initRole();

            $this->log('D', '删除应用', $appTitles);

            return $this->response->array(array('code' => 0, 'message' => '删除成功'));
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;

            return $this->response->array(array('code' => 1, 'message' => '删除失败'));
        }
    }
}
