<?php

namespace Admin;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Auth;

class UserTest extends \TestCase
{
    use WithoutMiddleware;

    public function testLogin()
    {
        $this->login();
        $this->assertGreaterThan(0, Auth::id());
    }

}
