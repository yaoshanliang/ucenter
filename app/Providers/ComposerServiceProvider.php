<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Session;
use View;
use Auth;
class ComposerServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		// exit;
		// $current_app = Session::put('apps', 1);
		// $current_app = Session::get('apps');
		// var_dump($current_app);
		// $current_app = 1;
		// View::composer('*', function($view) use ($current_app) {
			// $view->with('user', Auth::user()->username);
		// });
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

}
