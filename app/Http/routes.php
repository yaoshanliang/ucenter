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
        Route::get('/', 'HomeController@index');
        Route::get('/index', 'HomeController@index');

        // user
        Route::group(['prefix' => 'user'], function(){
            Route::get('/edit', 'UserController@edit');
            Route::get('/wechatCallback', 'UserController@wechatCallback');
            // Route::post('/edit', 'UserController@postEdit');
        });
        Route::resource('/user', 'UserController');

        // app
        Route::group(['prefix' => 'app'], function(){
        });
        Route::resource('/app', 'AppController');
    });
});

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => array('auth', 'role:developer|admin')], function() {
    Route::group([], function () {
        Route::put('/app/currentApp', 'AppController@putCurrentApp');
        Route::put('/app/currentRole', 'AppController@putCurrentRole');
    });
    Route::group(['middleware' => 'csrf'], function () {
        Route::get('/', function() { return Redirect::to('/admin/index'); });
        Route::get('/index', 'AdminController@index');

        // app，require developer role
        Route::group(['prefix' => 'app', 'middleware' => 'role:developer'], function() {
            // Route::post('/currentApp', 'AppController@currentApp');
        });
        Route::resource('app', 'AppController');
        // Route::controller('app', 'AppController');

        // user
        Route::group(['prefix' => 'user'], function(){
            Route::get('/invite', 'UserController@getInvite');
            Route::post('/invite', 'UserController@postInvite');
            Route::post('/lists', 'UserController@lists');
            Route::post('/delete', 'UserController@delete');
            Route::post('/remove', 'UserController@remove');
            Route::get('/all', 'UserController@all');
            Route::post('/allLists', 'UserController@allLists');
            Route::get('/fields', 'UserController@fields');
            Route::post('/fieldsLists', 'UserController@fieldsLists');
            Route::get('/{id}/roles', 'UserController@roles');
            Route::get('/{id}/selectOrUnselectRole/{role_id}', 'UserController@selectOrUnselectRole')->where('id', '[0-9]+')->where('role_id', '[0-9]+');
        });
        Route::resource('/user', 'UserController');

        // role
        Route::group(['prefix' => 'role'], function(){
            Route::post('/lists', 'RoleController@lists');
            Route::post('/delete', 'RoleController@delete');
            Route::get('/{id}/permission', 'RoleController@permission')->where('id', '[0-9]+');
            Route::post('/{id}/permissionLists', 'RoleController@permissionLists')->where('id', '[0-9]+');
            Route::get('/{id}/permissionSelected', 'RoleController@permissionSelected')->where('id', '[0-9]+');
            Route::post('/{id}/permissionSelectedLists', 'RoleController@permissionSelectedLists');
            Route::get('/{id}/permissionGroup/{permission_id}', 'RoleController@permissionGroup')->where('id', '[0-9]+')->where('permission_id', '[0-9]+');
            Route::get('/{id}/selectOrUnselectPermission/{permission_id}', 'RoleController@selectOrUnselectPermission')->where('id', '[0-9]+')->where('permission_id', '[0-9]+');
        });
        Route::resource('/role', 'RoleController');

        // permission
        Route::group(['prefix' => 'permission'], function(){
            Route::post('/lists', 'PermissionController@lists');
            Route::post('/delete', 'PermissionController@delete');
            Route::get('/group', 'PermissionController@group');
            Route::post('/groupLists', 'PermissionController@groupLists');
            Route::get('/createGroup', 'PermissionController@createGroup');
        });
        Route::resource('/permission', 'PermissionController');

        // file
        Route::group(['prefix' => 'file'], function(){
        });
        Route::resource('/file', 'FileController');

        // mail
        Route::group(['prefix' => 'mail'], function(){
        });
        Route::resource('/mail', 'MailController');

        // message
        Route::group(['prefix' => 'message'], function(){
        });
        Route::resource('/message', 'MessageController');

        // userlog
        Route::group(['prefix' => 'userlog'], function(){
            Route::post('/lists', 'UserLogController@lists');
            Route::post('/delete', 'UserLogController@delete');
        });
        Route::resource('/userlog', 'UserLogController');

    });
});

$api = app('api.router');
$api->version('v1', ['namespace' => 'App\Http\Controllers\Api\V1'], function ($api) {
    $api->group(['middleware' => 'oauth'], function ($api) {
        $api->get('user/info', 'UserController@getUserInfo');
        $api->get('user/role', 'UserController@getUserRole');
        $api->get('user/permission', 'UserController@getUserPermission');
        $api->put('user/info', 'UserController@edit');

        $api->put('app/secret', 'AppController@updateSecret');
        $api->post('sms/code', ['middleware' => ['oauth'], 'uses' => 'SmsController@sendCode']);
        $api->put('sms/code', 'SmsController@validateCode');
    });
    $api->group([], function ($api) {
        $api->post('oauth/accessToken', 'OauthController@getAccessToken');
        $api->get('oauth/authCode', ['middleware' => ['check-authorization-params'], 'uses' => 'OauthController@getAuthCode']);

        $api->put('app/currentApp', 'AppController@updateCurrentApp');
        $api->put('app/currentRole', 'AppController@updateCurrentRole');
    });
});

// Route::post('api/oauth/accessToken', 'Api\V1\OauthController@getAccessToken');
// Route::get('api/oauth/authCode', ['middleware' => ['check-authorization-params'], 'uses' => 'Api\V1\OauthController@getAuthCode']);

Route::get('/oauth/authorize', ['as' => 'oauth.authorize.get', 'middleware' => ['check-authorization-params', 'auth'], 'uses' => 'Oauth\OauthController@getAuthorize']);
Route::post('/oauth/authorize', ['as' => 'oauth.authorize.post', 'middleware' => ['csrf', 'check-authorization-params', 'auth'], 'uses' => 'Oauth\OauthController@postAuthorize']);
Route::get('/oauth/wechatCallback', 'Auth\AuthController@wechatCallback');
Route::post('/oauth/verifyPassword', 'Auth\AuthController@verifyPassword');
