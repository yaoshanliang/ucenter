<?php

namespace Admin;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Auth;
use App\Model\User;
use App\Model\App;

class AppTest extends \TestCase
{
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->startSession();

        $this->withoutMiddleware();

        $this->login();
    }

    public function testGetIndex()
    {
        $app = $this->call('GET', '/admin/app');
        $this->assertResponseOk();
    }

    public function testPostLists()
    {
        $params = 'draw=1&columns%5B0%5D%5Bdata%5D=id&columns%5B0%5D%5Bname%5D=&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=false&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B1%5D%5Bdata%5D=title&columns%5B1%5D%5Bname%5D=&columns%5B1%5D%5Bsearchable%5D=true&columns%5B1%5D%5Borderable%5D=true&columns%5B1%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B1%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B2%5D%5Bdata%5D=name&columns%5B2%5D%5Bname%5D=&columns%5B2%5D%5Bsearchable%5D=true&columns%5B2%5D%5Borderable%5D=true&columns%5B2%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B2%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B3%5D%5Bdata%5D=user_id&columns%5B3%5D%5Bname%5D=&columns%5B3%5D%5Bsearchable%5D=true&columns%5B3%5D%5Borderable%5D=true&columns%5B3%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B3%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B4%5D%5Bdata%5D=created_at&columns%5B4%5D%5Bname%5D=&columns%5B4%5D%5Bsearchable%5D=true&columns%5B4%5D%5Borderable%5D=true&columns%5B4%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B4%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B5%5D%5Bdata%5D=updated_at&columns%5B5%5D%5Bname%5D=&columns%5B5%5D%5Bsearchable%5D=true&columns%5B5%5D%5Borderable%5D=true&columns%5B5%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B5%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B6%5D%5Bdata%5D=id&columns%5B6%5D%5Bname%5D=&columns%5B6%5D%5Bsearchable%5D=true&columns%5B6%5D%5Borderable%5D=false&columns%5B6%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B6%5D%5Bsearch%5D%5Bregex%5D=false&order%5B0%5D%5Bcolumn%5D=5&order%5B0%5D%5Bdir%5D=desc&start=0&length=8&search%5Bvalue%5D=&search%5Bregex%5D=false';
        $app = $this->call('POST', '/admin/app/lists' . '?' . $params);
        $this->assertResponseOk();
    }

    public function testPostCreate()
    {
        $app = $this->post('/admin/app/create', ['title' => 'test', 'description' => 'test description', 'home_url' => 'http://test.com', 'login_url' => 'http://test.com/login']);
        $this->assertRedirectedTo('/admin/app');
    }

    public function testGetEdit()
    {
        $app = $this->call('GET', '/admin/app');
        $this->assertResponseOk();
    }

    public function testPostEdit()
    {
        $app = App::orderBy('id', 'desc')->first();
        $app = $this->put('/admin/app/edit/' . $app->id, ['old_name' => $app->name, 'name' => $app->name, 'title' => 'test title update', 'description' => 'test description update', 'home_url' => 'http://test.com/update', 'login_url' => 'http://test.com/login/update', 'secret' => 'test secret']);
        $this->assertRedirectedTo('/admin/app');
    }

    public function testDelete()
    {
        $app = App::orderBy('id', 'desc')->first();
        $app = $this->delete('/admin/app/delete', ['ids' => [$app->id]]);
        $this->assertResponseOk();
    }

}
