<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Api\V1\ApiController;

use App\Services\Api;

class SysController extends ApiController
{
    /*
     * 缓存
     */
    public function getCache()
    {
        $this->cacheSettings();
        $this->cacheApps();
        $this->cacheUsers();
        $this->cachePermissions();
        $this->cacheRoles();
        $this->cacheUserRole();
        $this->cacheWechat();

        return Api::apiReturn(SUCCESS, 'refresh cache');
    }
}
