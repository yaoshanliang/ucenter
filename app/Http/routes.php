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

Route::get('/', ['middleware' => ['auth', 'role:developer|admin'], function () {
    return Redirect::to('/admin/index');
}]);
Route::group(['middleware' => 'csrf'], function() {
    Route::controllers([
        'auth' => 'Auth\AuthController',
        'password' => 'Auth\PasswordController',
    ]);
});

Route::group(['prefix' => 'home', 'namespace' => 'Home', 'middleware' => ['auth']], function() {
    Route::group([], function () {
        Route::put('/app/currentApp', 'AppController@putCurrentApp');
        Route::put('/app/currentRole', 'AppController@putCurrentRole');
    });

    Route::group(['middleware' => ['csrf']], function () {
        Route::get('/', function() { return Redirect::to('/home/index'); });
        Route::get('/index', 'HomeController@getIndex');

        Route::controller('user', 'UserController');
        Route::controller('app', 'AppController');
    });
});

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth', 'role:developer|admin']], function() {
    Route::group([], function () {
    });
    Route::group(['middleware' => 'csrf'], function () {
        Route::get('/', function() { return Redirect::to('/admin/index'); });
        Route::get('/index', 'AdminController@index');

        Route::group(['prefix' => 'app', 'middleware' => 'role:developer'], function() {
            Route::controller('', 'AppController');
        });
        Route::controller('user', 'UserController');
        Route::controller('role', 'RoleController');
        Route::controller('permission', 'PermissionController');

        Route::controller('file', 'FileController');
        Route::controller('mail', 'MailController');
        Route::controller('message', 'MessageController');
        Route::controller('userlog', 'UserLogController');
        Route::controller('applog', 'AppLogController');
    });
});

$api = app('api.router');
$api->version('v1', ['namespace' => 'App\Http\Controllers\Api\V1'], function ($api) {
    $api->group(['middleware' => 'oauth'], function ($api) {
        $api->get('user/info', 'UserController@getInfo');
        $api->get('user/role', 'UserController@getRole');
        $api->get('user/permission', 'UserController@getPermission');
        $api->put('user/info', 'UserController@putInfo');

        $api->put('app/secret', 'AppController@putSecret');
        $api->post('sms/code', ['middleware' => ['oauth'], 'uses' => 'SmsController@postCode']);
        $api->put('sms/code', 'SmsController@putCode');

        $api->post('log/app', 'LogController@postApp');
        $api->post('log/sms', 'LogController@postSms');
        $api->post('log/email', 'LogController@postEmail');
    });
    $api->group([], function ($api) {
        $api->post('oauth/accessToken', 'OauthController@getAccessToken');
        $api->get('oauth/authCode', ['middleware' => ['check-authorization-params'], 'uses' => 'OauthController@getAuthCode']);
    });
});

app('api.exception')->register(function (Exception $exception) {
    $request = Illuminate\Http\Request::capture();
    return app('App\Exceptions\ApiHandler')->render($request, $exception);
});

Route::get('/oauth/authorize', ['as' => 'oauth.authorize.get', 'middleware' => ['check-authorization-params', 'auth'], 'uses' => 'Oauth\OauthController@getAuthorize']);
Route::post('/oauth/authorize', ['as' => 'oauth.authorize.post', 'middleware' => ['csrf', 'check-authorization-params', 'auth'], 'uses' => 'Oauth\OauthController@postAuthorize']);
Route::get('/oauth/wechatCallback', 'Auth\AuthController@wechatCallback');
Route::post('/oauth/verifyPassword', 'Auth\AuthController@verifyPassword');
