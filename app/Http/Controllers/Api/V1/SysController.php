<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Api\V1\ApiController;

use Cache;
use Queue;
use Config;
use App\Services\Api;
use App\Model\User;
use PhpSms;
use App\Jobs\Sms;

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
    }
}
