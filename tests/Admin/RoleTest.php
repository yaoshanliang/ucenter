<?php

namespace Admin;

use App\Model\Role;

class RoleTest extends AdminTest
{
    public function testGetIndex()
    {
        $app = $this->call('GET', '/admin/role');
        $this->assertResponseOk();
    }

    public function testPostLists()
    {
        $params = 'draw=1&columns%5B0%5D%5Bdata%5D=id&columns%5B0%5D%5Bname%5D=&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=false&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B1%5D%5Bdata%5D=title&columns%5B1%5D%5Bname%5D=&columns%5B1%5D%5Bsearchable%5D=true&columns%5B1%5D%5Borderable%5D=true&columns%5B1%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B1%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B2%5D%5Bdata%5D=name&columns%5B2%5D%5Bname%5D=&columns%5B2%5D%5Bsearchable%5D=true&columns%5B2%5D%5Borderable%5D=true&columns%5B2%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B2%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B3%5D%5Bdata%5D=description&columns%5B3%5D%5Bname%5D=&columns%5B3%5D%5Bsearchable%5D=true&columns%5B3%5D%5Borderable%5D=true&columns%5B3%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B3%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B4%5D%5Bdata%5D=created_at&columns%5B4%5D%5Bname%5D=&columns%5B4%5D%5Bsearchable%5D=true&columns%5B4%5D%5Borderable%5D=true&columns%5B4%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B4%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B5%5D%5Bdata%5D=updated_at&columns%5B5%5D%5Bname%5D=&columns%5B5%5D%5Bsearchable%5D=true&columns%5B5%5D%5Borderable%5D=true&columns%5B5%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B5%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B6%5D%5Bdata%5D=id&columns%5B6%5D%5Bname%5D=&columns%5B6%5D%5Bsearchable%5D=true&columns%5B6%5D%5Borderable%5D=false&columns%5B6%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B6%5D%5Bsearch%5D%5Bregex%5D=false&order%5B0%5D%5Bcolumn%5D=5&order%5B0%5D%5Bdir%5D=desc&start=0&length=8&search%5Bvalue%5D=&search%5Bregex%5D=false';
        $role = $this->call('POST', '/admin/role/lists' . '?' . $params);
        $this->assertResponseOk();
    }

    public function testPostCreate()
    {
        $role = $this->post('/admin/role/create', ['title' => 'test role', 'description' => 'test description', 'name' => 'test']);
        $this->assertRedirectedTo('/admin/role');
    }

    public function testGetEdit()
    {
        $role = Role::orderBy('id', 'desc')->first();
        $role = $this->call('GET', '/admin/role/edit/' . $role->id);
        $this->assertResponseOk();
    }

    public function testPostEdit()
    {
        $role = Role::orderBy('id', 'desc')->first();
        $role = $this->put('/admin/role/edit/' . $role->id, ['old_name' => $role->name, 'name' => $role->name, 'title' => 'test title update', 'description' => 'test description update']);
        $this->assertRedirectedTo('/admin/role');
    }

    public function testDelete()
    {
        $role = Role::orderBy('id', 'desc')->first();
        $role = $this->delete('/admin/role/delete', ['ids' => [$role->id]]);
        $this->assertResponseOk();
    }
}
