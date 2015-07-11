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
/*Route::get('login', [
	'middleware' => 'guest', 'as' => 'login', 'uses' => 'loginController@loginGet']);
Route::post('login', [
	'middleware' => 'guest', 'uses' => 'loginController@loginPost']);
Route::get('logout', [
	'middleware' => 'auth', 'as' => 'logout', 'uses' => 'loginController@logout']);
 */
Route::get('api', [
	'middleware' => 'guest', 'uses' => 'Api\ApiController@forbidden']);
// Route::get('api/get_token', [
	// 'middleware' => 'guest', 'uses' => 'Api\ApiController@get_token']);
Route::get('api/get_token', [
'uses' => 'Api\ApiController@get_token']);
Route::get('api/validate_token', [
'uses' => 'Api\ApiController@validate_token']);
Route::post('api', [
	'middleware' => 'guest', 'uses' => 'Api\ApiController@index']);
