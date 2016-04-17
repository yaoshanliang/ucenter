<?php

namespace Admin;

use Illuminate\Foundation\Testing\WithoutMiddleware;

class AdminTest extends \TestCase
{
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->startSession();

        $this->withoutMiddleware();

        $this->login();
    }
}
