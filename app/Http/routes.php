<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');
Route::get('auth/login/url/{url}', [
	'middleware' => 'guest', 'as' => 'login', 'uses' => 'Auth\AuthController@loginUrl']);
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
Route::group(['prefix' => 'home', 'namespace' => 'Home', 'middleware' => 'auth'], function() {
	  Route::get('/', 'HomeController@index');
	  Route::resource('user', 'AppController');
});
Route::group(['prefix' => 'admin', 'namespace' => 'Admin','middleware' => array('auth', 'permission')], function() {
	  Route::get('/', 'AdminController@index');
	  Route::resource('app', 'AppController');
	  Route::resource('user', 'UserController');
	  // Route::get('user', 'UserController');
	  // Route::match(['get', 'post'], 'user', 'UserController');
	  Route::post('user/lists', 'UserController@lists');
	  Route::post('user/delete', 'UserController@delete');
});
Route::group(['prefix' => 'api', 'namespace' => 'Api','middleware' => 'guest'], function() {
	  Route::get('/', 'ApiController@forbidden');
	  Route::resource('user', 'UserController');
	  // Route::resource('user', 'UserController');
});
Route::get('api/users', array('as'=>'api.users', 'uses'=>'Admin\UserController@getDatatable'));
// Route::get('api', [
	// 'middleware' => 'guest', 'uses' => 'Api\ApiController@forbidden']);
// Route::post('api', [
	// 'middleware' => 'guest', 'uses' => 'Api\ApiController@index']);
