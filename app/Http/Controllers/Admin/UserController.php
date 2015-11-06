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
		return view('admin.user.index');
	}

	public function lists(Request $request)
	{
		DB::enableQueryLog();
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
		$query_log = DB::getQueryLog();
		$ips = $request->ips();
		$ip = $ips[0];
		$ips = implode(',', $ips);
		foreach($query_log as $v) {
			$query_log_sql[]  = $v['query'];
		}
		$query_log_sql = implode(';', $query_log_sql);
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
		return view('admin.user.create');
	}

	//邀请加入
	public function getInvite()
	{
		return view('admin.user.invite');
	}

	public function postInvite(Request $request) {
		$this->validate($request, [
			'email' => 'required|email|unique:users',
			'username' => 'required|min:3|unique:users',
		]);

		$token = hash_hmac('sha256', str_random(40), env('APP_KEY'));

		//写入用户库
		$user = User::create(array('username' => $request->username, 'email' => $request->email));

		//写入密码重置
		$password_reset = DB::table('password_resets')->insert(
		    ['email' => $request->email, 'token' => $token, 'created_at' => date('Y-m-d H:i:s')]
		);

		//发送邀请邮件
		$mail = Queue::push(new SendEmail('invite', '邀请入驻用户中心', $token, $request->email));

		$log = Queue::push(new UserLog(1, Auth::user()->id, 'A', '邀请用户'));
		if($user && $mail && $password_reset) {
			session()->flash('success_message', '用户邀请成功');
			return view('admin.user.invite');
		} else {
			return Redirect::back()->withInput()->withErrors('保存失败！');
		}
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
		return view('admin.user.show');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		return view('admin.user.edit')->withUser(User::find($id));
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
