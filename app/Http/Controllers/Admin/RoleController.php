<?php namespace App\Http\Controllers\Admin;

use Zizaco\Entrust\EntrustPermission;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Role;
use App\User;
use App\Permission;
class RoleController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
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
