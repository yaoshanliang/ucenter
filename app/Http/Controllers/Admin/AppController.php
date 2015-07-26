<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\App;
use Redirect, Input, Auth;
use Illuminate\Pagination\Paginator;

class AppController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$count = App::count();
		$apps = App::orderBy('updated_at', 'desc')->paginate(5);
		return view('admin.app.index', compact('apps', 'count'));
		return view('admin.app.index')->withApps(App::all());
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
	public function store(Request $request)
	{
		$this->validate($request, [
			'app' => 'required|unique:apps',
			'app_name' => 'required',
			'app_home_url' => 'required',
			'app_login_url' => 'required',
			'app_secret' => 'required'
		]);

		$app = new App;
		$app->app = Input::get('app');
		$app->app_name = Input::get('app_name');
		$app->app_home_url = Input::get('app_home_url');
		$app->app_login_url = Input::get('app_login_url');
		$app->app_secret = Input::get('app_secret');

		if ($app->save()) {
			session()->flash('message', '应用添加成功');
			return Redirect::to('admin/app');
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
	public function edit($id)
	{
		return view('admin.app.edit')->withApp(App::find($id));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'app' => 'required|unique:apps,app,'.$id.'',
			'app_name' => 'required',
			'app_home_url' => 'required',
			'app_login_url' => 'required',
			'app_secret' => 'required'
		]);

		$app = App::find($id);
		$app->app = Input::get('app');
		$app->app_name = Input::get('app_name');
		$app->app_home_url = Input::get('app_home_url');
		$app->app_login_url = Input::get('app_login_url');
		$app->app_secret = Input::get('app_secret');

		if ($app->save()) {
			session()->flash('message', '应用修改成功');
			return Redirect::to('admin/app');
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
		$App = App::find($id);
		$App->delete();

		session()->flash('message', '应用删除成功');
		return Redirect::to('admin/app');
	}

}
