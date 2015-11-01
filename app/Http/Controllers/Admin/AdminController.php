<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\App;
use App\Setting;
use App\Role;
use Redirect, Input, Auth;
use Cache;
class AdminController extends Controller {

	public function __construct()
	{
		$settings_array = array('site_name', 'site_url', 'site_sub_name', 'site_description', 'copyright', 'support_email',
								'bei_an', 'tong_ji', 'page_size', 'expire');
		$settings_prefix = 'settings:';
		foreach($settings_array as $k => $v) {
			$settings[$v] = Cache::get($settings_prefix . $v, function() use ($v, $settings_prefix) {
				$setting = Setting::where('name', $v)->first(array('value'));
				Cache::forever($settings_prefix . $v, $setting['value']);
				return $setting['value'];
			});
		}
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('admin.index');
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
			return Redirect::to('admin');
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
		return view('admin.pages.edit')->withPage(Page::find($id));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$this->validate($request, [
			'title' => 'required|unique:pages,title,'.$id.'|max:255',
			'body' => 'required',
		]);

		$page = Page::find($id);
		$page->title = Input::get('title');
		$page->body = Input::get('body');
		$page->user_id = 1;//Auth::user()->id;

		if ($page->save()) {
			return Redirect::to('admin');
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
		$page = Page::find($id);
		$page->delete();

		return Redirect::to('admin');
	}

}
