<?php

namespace Api;

use Auth;

class UserTest extends ApiTest
{
    public function testPostUser()
    {
        $cache = $this->get('/api/sys/cache')->seeJson(['code' => 1]);
        $this->assertResponseOk();
    }

    public function testGetUser()
    {
        $user = $this->get('/api/user?access_token=' . $this->accessToken)->seeJson(['code' => 1]);
        $this->assertResponseOk();
    }

    public function testGetRole()
    {
        $user = $this->get('/api/user/role?access_token=' . $this->accessToken)->seeJson(['code' => 1]);
        $this->assertResponseOk();
    }

    public function testGetPermission()
    {
        $user = $this->get('/api/user/permission?access_token=' . $this->accessToken)->seeJson(['code' => 1]);
        $this->assertResponseOk();
    }

    public function testGetRolePermission()
    {
        $user = $this->get('/api/user/rolePermission?access_token=' . $this->accessToken)->seeJson(['code' => 1]);
        $this->assertResponseOk();
    }
}
