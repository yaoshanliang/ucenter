<?php

namespace Admin;

use Auth;

class UserTest extends \TestCase
{
    public function testLogin()
    {
        $user = $this->post('/auth/login', ['username' => 'admin', 'password' => '123456']);
        $this->assertGreaterThan(0, Auth::id());
    }

}
