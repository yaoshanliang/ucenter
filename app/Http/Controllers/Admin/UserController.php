<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Model\User;
use App\Model\UserRole;
use Redirect, Input, Auth;
use Illuminate\Pagination\Paginator;
use App\Services\Helper;

use Monolog\Logger;
use Monolog\Handler\RedisHandler;

use Cache;
use Queue;
use Session;
use App\Jobs\UserLog;
use App\Jobs\SendEmail;
use Mail;
use Config;

class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request) {
		return view('admin.user.index');
	}

	public function app(Request $request) {
		return view('admin.user.app');
	}

	public function lists(Request $request) {
		$app_user_ids = '';
		if(isset($_GET['type']) && $_GET['type'] == 'appuser') {
			$current_app_id = Session::get('current_app_id');
			$app_user_ids = UserRole::where('app_id', '=', $current_app_id)->lists('user_id');
		}
		// DB::enableQueryLog();
		$columns = $_POST['columns'];
		$order_column = $_POST['order']['0']['column'];//那一列排序，从0开始
		$order_dir = $_POST['order']['0']['dir'];//ase desc 升序或者降序
		$search = $_POST['search']['value'];//获取前台传过来的过滤条件
		$start = $_POST['start'];//从多少开始
		$length = $_POST['length'];//数据长度
		$fields = array('id', 'username', 'email', 'phone', 'created_at', 'updated_at');
		if(isset($_GET['type']) && $_GET['type'] == 'appuser') {
			$recordsTotal = User::whereIn('id', $app_user_ids)->count();
		} else {
			$recordsTotal = User::count();
		}
		if(strlen($search)) {
			$users = User::where(function ($query) use ($search, $app_user_ids) {
				if(isset($_GET['type']) && $_GET['type'] == 'appuser') {
					$query->whereIn('id', $app_user_ids);
				}
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
			$users = User::where(function ($query) use ($search, $app_user_ids) {
				if(isset($_GET['type']) && $_GET['type'] == 'appuser') {
					$query->whereIn('id', $app_user_ids);
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
	public function create() {
		return view('admin.user.create');
	}

	//邀请加入
	public function getInvite() {
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

		$ips = $request->ips();
		$ip = $ips[0];
		$ips = implode(',', $ips);
		$log = Queue::push(new UserLog(1, Auth::user()->id, 'A', '邀请用户', 'email：'. $request->email, '', $ip, $ips));
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
	public function store() {
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id) {
		return view('admin.user.show');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id) {
        $dispatcher = app('Dingo\Api\Dispatcher');
        $data = $dispatcher->get('api/user/user_info?user_id=1000&access_token=iblRrfFdctRVIxsuTzPDx5TgbGiAobhxjKItRPzO');
		return view('admin.user.edit')->withUser($data['data']);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id) {
		//
	}

	//删除
	public function delete() {
		DB::beginTransaction();
		try {
			$ids = $_POST['ids'];
			$result = User::whereIn('id', $ids)->delete();

			DB::commit();
			Helper::jsonp_return(0, '删除成功', array('deleted_num' => $result));
		} catch (Exception $e) {
			DB::rollBack();
			throw $e;
			Helper::jsonp_return(1, '删除失败', array('deleted_num' => 0));
		}
	}

	//从当前应用中移出用户
	public function remove() {
		DB::beginTransaction();
		try {
			$ids = $_POST['ids'];
			$result = UserRole::where('app_id', Session::get('current_app_id'))->whereIn('user_id', $ids)->delete();

			DB::commit();
			Helper::jsonp_return(0, '移除成功', array('deleted_num' => $result));
		} catch (Exception $e) {
			DB::rollBack();
			throw $e;
			Helper::jsonp_return(1, '移除失败', array('deleted_num' => 0));
		}
	}
}
