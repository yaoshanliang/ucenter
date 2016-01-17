<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Model\UserLog;
use Session;

class UserLogController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('admin.userlog.index');
	}

	public function app(Request $request) {
		return view('admin.userlog.app');
	}

	public function lists(Request $request) {
		// DB::enableQueryLog();
		$columns = $_POST['columns'];
		$order_column = $_POST['order']['0']['column'];//那一列排序，从0开始
		$order_dir = $_POST['order']['0']['dir'];//ase desc 升序或者降序
		$search = $_POST['search']['value'];//获取前台传过来的过滤条件
		$start = $_POST['start'];//从多少开始
		$length = $_POST['length'];//数据长度
		$fields = array('id', 'user_id', 'type', 'title', 'data', 'ip', 'pushed_at', 'created_at');
		if(isset($_GET['type']) && $_GET['type'] == 'app') {
			$recordsTotal = UserLog::where('app_id', Session::get('current_app_id'))->count();
		} else {
			$recordsTotal = UserLog::count();
		}
		if(strlen($search)) {
			$users = UserLog::where(function ($query) use ($search) {
				if(isset($_GET['type']) && $_GET['type'] == 'app') {
					$query->where('app_id', Session::get('current_app_id'));
				}})
				->where("user_id" , 'LIKE',  '%' . $search . '%')
					->orWhere("type" , 'LIKE',  '%' . $search . '%')
					->orWhere("title" , 'LIKE',  '%' . $search . '%')
					->orWhere("data" , 'LIKE',  '%' . $search . '%')
					->orWhere("ip" , 'LIKE',  '%' . $search . '%')
				->orderby($columns[$order_column]['data'], $order_dir)
				->skip($start)
				->take($length)
				->get($fields)
				->toArray();
			$recordsFiltered = count($users);
		} else {
			$users = UserLog::where(function ($query) use ($search) {
				if(isset($_GET['type']) && $_GET['type'] == 'app') {
					$query->where('app_id', Session::get('current_app_id'));
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
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
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
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
