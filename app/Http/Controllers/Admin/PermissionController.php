<?php namespace App\Http\Controllers\Admin;

use Zizaco\Entrust\EntrustPermission;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Model\Role;
use App\Model\User;
use App\Model\Permission;
use Session;
class PermissionController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request) {
		return view('admin.permission.index');
		// $owner = new Role();
		// $owner->app_id = 2;
		// $owner->name         = 'owner';
		// $owner->title = 'Project Owner'; // optional
		// $owner->description  = 'User is the owner of a given project'; // optional
		// $owner->save();
		// $role = Role::find(16);
		// $role->delete();
		// return view('admin.role.index');
		$admin = Role::find(20);
		// var_dump($admin);exit;
		$user = User::find(5);
		// $user->attachRole($admin);
		$user->detachRole($admin);
		$createPost = new Permission();
		$createPost->name         = 'create-post';
		$createPost->app_id         = 2;
		$createPost->title = 'Create Posts'; // optional
		$createPost->description  = 'create new blog posts'; // optional
		// $createPost->save();
		// $admin->attachPermission($createPost);
	}

	public function app(Request $request) {
		return view('admin.permission.app');
	}

	public function lists(Request $request) {
		$app_user_ids = '';
		// if(isset($_GET['type']) && $_GET['type'] == 'app') {
			// $current_app_id = Session::get('current_app_id');
			// $app_user_ids = Role::where('app_id', '=', $current_app_id)->lists('id');
		// }
		// DB::enableQueryLog();
		$columns = $_POST['columns'];
		$order_column = $_POST['order']['0']['column'];//那一列排序，从0开始
		$order_dir = $_POST['order']['0']['dir'];//ase desc 升序或者降序
		$search = $_POST['search']['value'];//获取前台传过来的过滤条件
		$start = $_POST['start'];//从多少开始
		$length = $_POST['length'];//数据长度
		$fields = array('id', 'name', 'title', 'description', 'created_at', 'updated_at');
		if(isset($_GET['type']) && $_GET['type'] == 'app') {
			$recordsTotal = Permission::where('app_id', Session::get('current_app_id'))->count();
		} else {
			$recordsTotal = Permission::count();
		}
		if(strlen($search)) {
			$roles = Permission::where(function ($query) use ($search) {
				if(isset($_GET['type']) && $_GET['type'] == 'app') {
					$query->where('app_id', Session::get('current_app_id'));
				}})
				->where("name" , 'LIKE',  '%' . $search . '%')
				->orWhere("title" , 'LIKE',  '%' . $search . '%')
				->orWhere("description" , 'LIKE',  '%' . $search . '%')
				->orderby($columns[$order_column]['data'], $order_dir)
				->skip($start)
				->take($length)
				->get($fields)
				->toArray();
			$recordsFiltered = count($roles);
		} else {
			$roles = Permission::where(function ($query) use ($search) {
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
		$return_data = array(
							"draw" => intval($_POST['draw']),
							"recordsTotal" => intval($recordsTotal),
							"recordsFiltered" => intval($recordsFiltered),
							"data" => $roles
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
		return view('admin.permission.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request) {
        $permission = Permission::create(array(
            'app_id' => Session::get('current_app_id'),
            'name' => $request->name,
			'title' => $request->title,
			'description' => $request->description,
		));
		if ($permission) {
			session()->flash('success_message', '权限添加成功');
			return redirect('/admin/permission/app');
		} else {
			return redirect()->back()->withInput()->withErrors('保存失败！');
		}
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

	public static function getAllRoles()
	{
		$roles = Role::all();
		return $roles;
	}
}
