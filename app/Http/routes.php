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
Route::group(['prefix' => 'admin', 'namespace' => 'Admin','middleware' => array('auth', 'role')], function() {
      Route::get('/', 'AdminController@index');
      Route::get('/index', 'AdminController@index');
      Route::get('/user/index', 'UserController@index');
      Route::get('/user/app', 'UserController@app');
      Route::get('user/invite', 'UserController@getInvite');
      Route::post('user/invite', 'UserController@postInvite');
      Route::post('user/lists', 'UserController@lists');
      Route::post('user/delete', 'UserController@delete');
      Route::post('user/remove', 'UserController@remove');
      Route::resource('user', 'UserController');

      Route::get('/app/index', 'AppController@index');
      Route::get('/app/app', 'AppController@app');
      Route::post('/app/lists', 'AppController@lists');
      Route::post('/app/delete', 'AppController@delete');
      Route::resource('app', 'AppController');

      Route::get('/role/index', 'RoleController@index');
      Route::get('/role/app', 'RoleController@app');
      Route::get('/role/{id}/permission', 'RoleController@permission');
      Route::get('/role/{id}/permissionEdit', 'RoleController@permissionEdit');
      Route::post('/role/lists', 'RoleController@lists');
      Route::post('/role/{id}/permission_lists', 'RoleController@permissionLists')->where('id', '[0-9]+');
      Route::post('/role/{id}/permission_edit_lists', 'RoleController@permissionEditLists')->where('id', '[0-9]+');
      Route::post('/role/delete', 'RoleController@delete');
      Route::resource('role', 'RoleController');

      Route::get('/permission/index', 'PermissionController@index');
      Route::get('/permission/app', 'PermissionController@app');
      Route::post('/permission/lists', 'PermissionController@lists');
      Route::post('/permission/delete', 'PermissionController@delete');
      Route::resource('/permission', 'PermissionController');

      Route::get('/userlog/index', 'UserLogController@index');
      Route::get('/userlog/app', 'UserLogController@app');
      Route::post('/userlog/lists', 'UserLogController@lists');
      Route::post('/userlog/delete', 'UserLogController@delete');
      Route::resource('/userlog', 'UserLogController');
      // Route::get('app/{id}/edit', 'AppController@edit', function(Request $request)
          // {
        // $id = $this->route('app');
        // return App::where('id', $id)->where('user_id', Auth::id())->exists();
          // });
      // Route::post('app/{id}/edit', 'AppController@update');
});
// Route::controllers([
    // '/api/user' => 'Api\UserController',
// ]);
// Route::group(['prefix' => 'api/v1', 'namespace' => 'Api','middleware' => 'guest'], function() {
// Route::group(['prefix' => 'api', 'middleware' => 'guest'], function() {
      // Route::get('/', 'V1\ApiController@forbidden');
      // Route::resource('user', 'UserController');
      // Route::resource('user', 'UserController');
// });
// Route::get('api', ['uses' => 'Api\ApiController@forbidden']);
/*
Route::get('api/users', array('as'=>'api.users', 'uses'=>'Admin\UserController@getDatatable'));
// Route::get('/api/user/{action}', ['uses' => 'Api\UserController@$action']);
Route::get('/api/user/getSelfInfo', ['uses' => 'Api\UserController@getSelfInfo']);
Route::get('/api/login', ['uses' => 'Api\LoginController@getLogin']);
Route::post('/api/login', ['uses' => 'Api\LoginController@postLogin']);
Route::post('api', ['uses' => 'Api\ApiController@index']);
 */
// $api = app('Dingo\Api\Routing\Router');
// Route::get('api/me', 'App\Http\Controllers\Api\V1\UserController@me');
$api = app('api.router');
$api->version('v1', function ($api) {
    $api->post('authenticate', 'App\Http\Controllers\Api\V1\AuthenticateController@authenticate');
    $api->get('me', 'App\Http\Controllers\Api\V1\UserController@me');
    // $api->get('api/me', 'App\Http\Controllers\Api\V1\UserController@me');
    // $api->post('auth/login', 'App\Http\Controllers\Api\V1\AuthenticateController@authenticate');
});

$api->version('v1', ['middleware' => 'oauth'], function ($api) {
    $api->get('user/self_info', 'App\Http\Controllers\Api\V1\UserController@me');
    $api->get('user/user_info', 'App\Http\Controllers\Api\V1\UserController@getUserInfo');
    $api->post('app/setCurrentApp', 'App\Http\Controllers\Api\V1\AppController@setCurrentApp');
    $api->post('app/setCurrentRole', 'App\Http\Controllers\Api\V1\AppController@setCurrentRole');
    // $api->get('me', ['scopes' => 'read_user_data', function () {
            // Only access tokens with the "read_user_data" scope will be given access.
    // }]);
});
// Route::get('oauth/authorize', ['as' => 'oauth.authorize.get', 'middleware' => ['check-authorization-params', 'auth'], function() {
Route::get('oauth/authorize', ['as' => 'oauth.authorize.get', 'middleware' => ['auth', 'check-authorization-params'], function() {
   $authParams = Authorizer::getAuthCodeRequestParams();

   $formParams = array_except($authParams,'client');

   $formParams['client_id'] = $authParams['client']->getId();

   $formParams['scope'] = implode(config('oauth2.scope_delimiter'), array_map(function ($scope) {
          return $scope->getId();
      }, $authParams['scopes']));

   return View::make('oauth.authorize', ['params' => $formParams, 'client' => $authParams['client']]);
}]);
Route::post('oauth/authorize', ['as' => 'oauth.authorize.post', 'middleware' => ['csrf', 'check-authorization-params', 'auth'], function() {

    $params = Authorizer::getAuthCodeRequestParams();
    $params['user_id'] = Auth::user()->id;
    $redirectUri = '/';

    // If the user has allowed the client to access its data, redirect back to the client with an auth code.
    if (Request::has('approve')) {
        $redirectUri = Authorizer::issueAuthCode('user', $params['user_id'], $params);
    }

    // If the user has denied the client to access its data, redirect back to the client with an error message.
    if (Request::has('deny')) {
        $redirectUri = Authorizer::authCodeRequestDeniedRedirectUri();
    }

    return Redirect::to($redirectUri);
}]);
Route::post('oauth/access_token', function() {
    return Response::json(array('code' => 1, 'message' => '获取access_token成功', 'data' => Authorizer::issueAccessToken()));
});
// $dispatcher = app('Dingo\Api\Dispatcher');
