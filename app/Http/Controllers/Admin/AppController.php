<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Requests\AppRequest;
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
			'user_id' => Auth::user()->id
		));

		if ($app) {
			session()->flash('success_message', '应用添加成功');
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
			// 'title' => 'required',
			// 'home_url' => 'required|url',
			// 'login_url' => 'required|url',
			// 'secret' => 'required'
		]);

		$app = App::where('id', $id)->update(array('name' => $request->name,
			'title' => $request->title,
			'description' => $request->description,
			'home_url' => $request->home_url,
			'login_url' => $request->login_url,
			'secret' => $request->secret,
			'user_id' => Auth::user()->id
		));

		if ($app) {
			session()->flash('success_message', '应用修改成功');
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
