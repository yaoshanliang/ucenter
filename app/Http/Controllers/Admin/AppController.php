<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Requests\AppRequest;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Model\App;
use Auth;
use DB;
use Queue;
use App\Model\Role;
use App\Model\Permission;
use App\Jobs\UserLog;
use App\Services\Api;
use Cache;
use Config;
use Session;
use Dingo\Api\Routing\Helpers;

class AppController extends Controller
{
    // 应用列表
    public function getIndex()
    {
        return view('admin.app.index');
    }

    public function postLists(Request $request)
    {
        $fields = array('id', 'name', 'title', 'user_id', 'created_at', 'updated_at');
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

    // 创建应用
    public function getCreate()
    {
        return view('admin.app.create');
    }

    public function postCreate(Request $request)
    {
        $this->validate($request, array(
            'title' => 'required',
            'description' => 'required',
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
            return redirect('/admin/app');
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

        return view('admin.app.edit')->with(['app' => $app, 'accessToken' => parent::accessToken()]);
    }

    public function putEdit(AppRequest $request, $id)
    {
        $this->validate($request, array(
            'title' => 'required',
            'home_url' => 'required|url',
            'login_url' => 'required|url',
            'secret' => 'required'
        ));

        $app = App::where('id', $id)->update(array(
            'title' => $request->title,
            'description' => $request->description,
            'home_url' => $request->home_url,
            'login_url' => $request->login_url,
            'user_id' => Auth::id()
        ));

        $oauth_client = DB::table('oauth_clients')->where('id', $request->old_name)->update(array(
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
            return redirect('/admin/app');
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

            return $this->response->array(array('code' => 1, 'message' => '删除成功'));
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;

            return $this->response->array(array('code' => 0, 'message' => '删除失败'));
        }
    }
}
