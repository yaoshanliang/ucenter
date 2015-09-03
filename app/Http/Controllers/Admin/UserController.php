<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\User;
use Redirect, Input, Auth;
use Illuminate\Pagination\Paginator;
class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// $count = User::count();
		// $users = User::orderBy('updated_at', 'desc')->paginate(5);
		// return view('admin.user.index', compact('users'));
		return view('admin.user.index');
	}

	public function lists()
	{
		$draw = $_POST['draw']+1;//这个值作者会直接返回给前台

		//排序
		$columns = $_POST['columns'];
		$order_column = $_POST['order']['0']['column'];//那一列排序，从0开始
		$order_dir = $_POST['order']['0']['dir'];//ase desc 升序或者降序]
		$orderSql = '';
		if(isset($order_column)) {
			$orderSql = ' order by ' . $columns[$order_column]['data'] . ' ' . $order_dir;
		}
		// echo $orderSql;
		$search = $_POST['search']['value'];//获取前台传过来的过滤条件

		$start = $_POST['start'];//从多少开始
		$length = $_POST['length'];//数据长度
		$limitSql = '';
		$limitFlag = isset($_POST['start']) && $length != -1 ;
		if ($limitFlag ) {
		    $limitSql = " LIMIT ".intval($start).", ".intval($length);
		}

		//定义查询数据总记录数sql
		$sumSql = "SELECT count(id) as sum FROM `users`";
		//条件过滤后记录数 必要
		$recordsFiltered = 0;
		//表的总记录数 必要
		$recordsTotal = 0;
		//分页
		$start = $_POST['start'];//从多少开始
		$length = $_POST['length'];//数据长度
		$users = User::where("username" , 'LIKE',  '%' . $search . '%')
			->orWhere("email" , 'LIKE',  '%' . $search . '%')
			->orWhere("phone" , 'LIKE',  '%' . $search . '%')
			->orderby($columns[$order_column]['data'], $order_dir)
			->skip($start)
			->take($length)
			->get(array('id', 'username', 'email', 'phone', 'created_at', 'updated_at'))
			->toArray();
		$recordsTotal = User::count();
		if(strlen($search)) {
			$recordsFiltered = count($users);
		} else {
			$recordsFiltered = $recordsTotal;
		}
		$draw = $_POST['draw'];
		$jsonp = preg_match('/^[$A-Z_][0-9A-Z_$]*$/i', $_GET['callback']) ? $_GET['callback'] : false;
		if ( $jsonp ) {
		    echo $jsonp.'('.json_encode(array(
				"draw" => intval($draw),
				"recordsTotal" => intval($recordsTotal),
				"recordsFiltered" => intval($recordsFiltered),
				"data" => $users
				),JSON_UNESCAPED_UNICODE) . ');';
		}
		// return json_encode($output);
		// var_dump($_REQUEST);
		// exit;
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
