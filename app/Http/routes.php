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

Route::get('/', 'HomeController@index');

Route::get('home', 'HomeController@index');

Route::get('auth/login/url/{url}', [
	'middleware' => 'guest', 'as' => 'login', 'uses' => 'Auth\AuthController@loginUrl']);
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
Route::get('api', [
	'middleware' => 'guest', 'uses' => 'Api\ApiController@forbidden']);
Route::post('api', [
	'middleware' => 'guest', 'uses' => 'Api\ApiController@index']);
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'auth'], function() {
	  Route::get('/', 'AppController@index');
	  Route::resource('app', 'AppController');
});
