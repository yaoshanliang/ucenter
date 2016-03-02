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

    // 获取当前应用当前用户的角色
    public function getUserRole(Request $request)
    {
        $roles = Cache::get(Config::get('cache.user_role.app_id') . parent::$currentAppId . ':user_id:' . parent::$currentUserId);

        if (empty($roles['roles'])) {
            return $this->response->array(array('code' => 0, 'message' => '当前用户没有角色'));
        }

        return $this->response->array(array('code' => 1, 'message' => '获取角色成功', 'data' => $roles));
    }

    // 获取当前应用当前用户的权限
    public function getUserPermission(Request $request)
    {
        $roles = Cache::get(Config::get('cache.user_role.app_id') . parent::$currentAppId . ':user_id:' . parent::$currentUserId);
        if (empty($roles['roles'])) {
            return $this->response->array(array('code' => 0, 'message' => '当前用户没有权限'));
        }
        $data['user_id'] = $roles['user_id'];
        $permissions = array();
        foreach ($roles['roles'] as $value) {
            foreach ($value['permissions'] as $v) {
                $permissions[$v['id']] = $v;
            }
        }

        if (empty($permissions)) {
            return $this->response->array(array('code' => 0, 'message' => '当前用户没有权限'));
        }

        $data['permissions'] = array_values($permissions);

        return $this->response->array(array('code' => 1, 'message' => '获取权限成功', 'data' => $data));
    }
}
