<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Dingo\Api\Routing\Helpers;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use App\Model\UserRole;
use Cache;
use Config;

class RoleController extends ApiController
{
    use Helpers;

    public function getUserRole(Request $request)
    {
        $roleIdsArray = UserRole::where('app_id', parent::$currentAppId)
            ->where('user_id', parent::$currentUserId)->lists('role_id');
        if (empty($roleIdsArray)) {
            return $this->response->array(array('code' => 1, 'message' => '验证成功'));
        }
        foreach ($roleIdsArray as $v) {
            $roles[] = Cache::get(Config::get('cache.roles') . $v);
        }
        return $this->response->array(array('code' => 1, 'message' => '获取角色成功', 'data' => $roles));
    }
}
