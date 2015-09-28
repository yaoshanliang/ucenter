<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\User;
use Redirect, Input, Auth;
use Illuminate\Pagination\Paginator;
use App\Services\Helper;

use Monolog\Logger;
use Monolog\Handler\RedisHandler;

use Cache;
use Bus;
use Queue;
use App\Commands\UserLog;
use App\Commands\SendEmail;
use PC;
use Mail;

class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$logger = new Logger('my_logger');
		// $logger->pushHandler(new RedisHandler(Cache::connection(), 'log', 'prod'));

		// $logger->addInfo('My logger is now ready', array('username' => 'Seldaek'));
		// $logger->pushProcessor(function ($record) {
			// $record['formatted']['dummy'] = 'Hello world!';

			// return $record;
		// });
		// var_dump(Request->ip());
		$ips = $request->ips();
		$ip = $ips[0];
		$ips = implode(',', $ips);
		$log = Queue::push(new UserLog(2, 5, 'S', '用户', 'admin', 'select * from users;', $ip, $ips));
		$message = 1223;
		Queue::push(new SendEmail($message));
		$data = array('id'=>1);
		// Mail::queue('emails.welcome', $data, function($message)
		// {
			// $message->to('1329517386@qq.com', 'John Smith')->subject('Welcome!');
		// });

		return view('admin.user.index');
	}

	public function lists()
	{
		$columns = $_POST['columns'];
		$order_column = $_POST['order']['0']['column'];//那一列排序，从0开始
		$order_dir = $_POST['order']['0']['dir'];//ase desc 升序或者降序
		$search = $_POST['search']['value'];//获取前台传过来的过滤条件
		$start = $_POST['start'];//从多少开始
		$length = $_POST['length'];//数据长度
		$fields = array('id', 'username', 'email', 'phone', 'created_at', 'updated_at');
		$recordsTotal = User::count();
		if(strlen($search)) {
			$users = User::where(function ($query) use ($search) {
				$query->where("username" , 'LIKE',  '%' . $search . '%')
					->orWhere("email" , 'LIKE',  '%' . $search . '%')
					->orWhere("phone" , 'LIKE',  '%' . $search . '%');
				})
				->orderby($columns[$order_column]['data'], $order_dir)
				->skip($start)
				->take($length)
				->get($fields)
				->toArray();
			$recordsFiltered = count($users);
		} else {
			$users = User::orderby($columns[$order_column]['data'], $order_dir)
				->skip($start)
				->take($length)
				->get($fields)
				->toArray();
			$recordsFiltered = $recordsTotal;
		}
		$jsonp = preg_match('/^[$A-Z_][0-9A-Z_$]*$/i', $_GET['callback']) ? $_GET['callback'] : false;
		if($jsonp) {
		    echo $jsonp.'('.json_encode(array(
				"draw" => intval($_POST['draw']),
				"recordsTotal" => intval($recordsTotal),
				"recordsFiltered" => intval($recordsFiltered),
				"data" => $users
				),JSON_UNESCAPED_UNICODE) . ');';
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

	//删除
	public function delete()
	{
		DB::beginTransaction();
		try{
			$ids = explode(',', $_POST['ids']);
			$result = User::whereIn('id', $ids)->delete();

			DB::commit();
			Helper::jsonp_return(0, '删除成功', array('deleted_num' => $result));
		} catch (Exception $e) {
			DB::rollBack();
			throw $e;
			Helper::jsonp_return(1, '删除失败', array('deleted_num' => 0));
		}
	}
}
