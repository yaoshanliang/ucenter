<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Model\User;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    // use WithoutMiddleware;

    protected $user;

    protected $baseUrl = 'http://ucenter.szjlxh.com';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->singleton(
              'Illuminate\Contracts\Debug\ExceptionHandler',
                'App\Exceptions\Handler'
              );
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        return $app;
    }

    public function login()
    {
        $this->user = $this->post('/auth/login', ['username' => 'admin', 'password' => '123456']);
        $this->be(User::where('username', 'admin')->first());
        $this->assertSessionHas('current_app');
        $this->assertSessionHas('current_role');
    }

    public function test()
    {
    }
}
