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
use App\Jobs\UserLog;

class AppController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('admin.app.index');
	}

	public function app()
	{
		return view('admin.app.app');
	}

	public function lists(Request $request)
	{
		$user_id = Auth::id();
		$columns = $_POST['columns'];
		$order_column = $_POST['order']['0']['column'];
		$order_dir = $_POST['order']['0']['dir'];
		$search = $_POST['search']['value'];
		$start = $_POST['start'];
		$length = $_POST['length'];
		$fields = array('id', 'name', 'title', 'user_id', 'created_at', 'updated_at');
		if(isset($_GET['type']) && $_GET['type'] == 'app') {
			$recordsTotal = App::where('user_id', $user_id)->count();
		} else {
			$recordsTotal = App::count();
		}
		if(strlen($search)) {
			$users = App::where(function ($query) use ($search, $user_id) {
				if(isset($_GET['type']) && $_GET['type'] == 'app') {
					$query->where('user_id', $user_id);
				}
				$query->where("name" , 'LIKE',  '%' . $search . '%')
					->orWhere("title" , 'LIKE',  '%' . $search . '%');
				})
				->orderby($columns[$order_column]['data'], $order_dir)
				->skip($start)
				->take($length)
				->get($fields)
				->toArray();
			$recordsFiltered = count($users);
		} else {
			$users = App::where(function ($query) use ($search, $user_id) {
				if(isset($_GET['type']) && $_GET['type'] == 'app') {
					$query->where('user_id', $user_id);
				}})
				->orderby($columns[$order_column]['data'], $order_dir)
				->skip($start)
				->take($length)
				->get($fields)
				->toArray();
			$recordsFiltered = $recordsTotal;
		}
		// $query_log = DB::getQueryLog();
		$ips = $request->ips();
		$ip = $ips[0];
		$ips = implode(',', $ips);
		// foreach($query_log as $v) {
			// $query_log_sql[]  = $v['query'];
		// }
		// $query_log_sql = implode(';', $query_log_sql);
		// $log = Queue::push(new UserLog(1, Auth::user()->id, 'S', '用户', '', $query_log_sql, $ip, $ips));
		$return_data = array(
							"draw" => intval($_POST['draw']),
							"recordsTotal" => intval($recordsTotal),
							"recordsFiltered" => intval($recordsFiltered),
							"data" => $users
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
			Auth::user()->can('delete-all-app');
			$result = App::whereIn('id', $ids)->delete();

            DB::table('oauth_clients')->where('id', $id)->delete();
			DB::commit();
			Helper::jsonp_return(0, '删除成功', array('deleted_num' => $result));
		} catch (Exception $e) {
			DB::rollBack();
			throw $e;
			Helper::jsonp_return(1, '删除失败', array('deleted_num' => 0));
		}
	}

}
