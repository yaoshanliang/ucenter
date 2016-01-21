<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Requests\AppRequest;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Model\App;
use Redirect, Input, Auth;
use Illuminate\Support\Facades\DB;
use App\Services\Helper;
use Queue;
use App\Model\Role;
use App\Model\Permission;
use App\Jobs\UserLog;

class AppController extends Controller {

	public function index()
	{
		return view('admin.app.index');
	}

	public function lists(Request $request)
	{
		$fields = array('id', 'name', 'title', 'user_id', 'created_at', 'updated_at');
        $searchFields = array('name', 'title');

        $users = App::where('user_id', Auth::id())
            ->whereSearch($request, $searchFields)
            ->orderByArray($request)
			->skip($request->start)
			->take($request->length)
			->get($fields)
			->toArray();
		$recordsFiltered = count($users);
		$recordsTotal = App::where('user_id', Auth::id())->count();

		$return_data = array(
            'draw' => intval($request->draw),
			'recordsTotal' => intval($recordsTotal),
			'recordsFiltered' => intval($recordsFiltered),
			'data' => $users
		);
		$jsonp = preg_match('/^[$A-Z_][0-9A-Z_$]*$/i', $_GET['callback']) ? $_GET['callback'] : false;
		if($jsonp) {
		    echo $jsonp . '(' . json_encode($return_data, JSON_UNESCAPED_UNICODE) . ');';
		} else {
		    echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('admin.app.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(AppRequest $request)
	{
		/*$this->validate($request, [
			'name' => 'required|unique:apps',
			'title' => 'required',
			'home_url' => 'required|url',
			'login_url' => 'required|url',
			'secret' => 'required'
		]);*/

		$app = App::create(array('name' => $request->name,
			'title' => $request->title,
			'description' => $request->description,
			'home_url' => $request->home_url,
			'login_url' => $request->login_url,
			'secret' => $request->secret,
			'user_id' => Auth::id()
		));

        // 接入oauth_clients
        DB::table('oauth_clients')->insert(array(
            'id' => $request->name,
            'secret' => $request->secret,
            'name' => $request->title,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));

        $role = Role::create(array(
            'app_id' => $app->id,
            'name' => 'developer',
			'title' => '开发者',
			'description' => '开发者',
		));

        DB::table('user_role')->insert(array(
            'user_id' => Auth::id(),
            'app_id' => $app->id,
            'role_id' => $role->id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));

		$ips = $request->ips();
		$ip = $ips[0];
		$ips = implode(',', $ips);
		$log = Queue::push(new UserLog(1, Auth::id(), 'A', '新增应用', 'name : ' . $request->name . '; title : ' . $request->title, '', $ip, $ips));
		if ($app) {
			session()->flash('success_message', '应用添加成功');
			return Redirect::to('/admin/app/app');
		} else {
			return Redirect::back()->withInput()->withErrors('保存失败！');
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(AppRequest $request, $id)
	{
		return view('admin.app.edit')->withApp(App::find($id));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(AppRequest $request, $id)
	{
		$this->validate($request, [
			'name' => 'required|unique:apps,name,'.$id.'',
		]);

		$app = App::where('id', $id)->update(array('name' => $request->name,
			'title' => $request->title,
			'description' => $request->description,
			'home_url' => $request->home_url,
			'login_url' => $request->login_url,
			'secret' => $request->secret,
			'user_id' => Auth::user()->id
		));

        DB::table('oauth_clients')->where('id', $id)->update(array(
            'id' => $request->name,
            'secret' => $request->secret,
            'name' => $request->title,
            'updated_at' => date('Y-m-d H:i:s')
        ));

		if ($app) {
			session()->flash('success_message', '应用修改成功');
			return Redirect::to('/admin/app/app');
		} else {
			return Redirect::back()->withInput()->withErrors('保存失败！');
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		return false;
	}

	//删除
	public function delete()
	{
		DB::beginTransaction();
		try {
			$ids = $_POST['ids'];
			// Auth::user()->can('delete-all-app');
			$result = App::whereIn('id', $ids)->delete();

            DB::table('oauth_clients')->whereIn('id', $ids)->delete();
			DB::commit();
			Helper::jsonp_return(0, '删除成功', array('deleted_num' => $result));
		} catch (Exception $e) {
			DB::rollBack();
			throw $e;
			Helper::jsonp_return(1, '删除失败', array('deleted_num' => 0));
		}
	}

}
