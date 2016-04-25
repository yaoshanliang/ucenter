<?php

namespace Admin;

use App\Model\Permission;

class PermissionTest extends AdminTest
{
    public function testGetIndex()
    {
        $app = $this->call('GET', '/admin/permission');
        $this->assertResponseOk();
    }

    public function testPostLists()
    {
        $params = 'draw=1&columns%5B0%5D%5Bdata%5D=id&columns%5B0%5D%5Bname%5D=&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=false&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B1%5D%5Bdata%5D=group_name&columns%5B1%5D%5Bname%5D=&columns%5B1%5D%5Bsearchable%5D=true&columns%5B1%5D%5Borderable%5D=true&columns%5B1%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B1%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B2%5D%5Bdata%5D=title&columns%5B2%5D%5Bname%5D=&columns%5B2%5D%5Bsearchable%5D=true&columns%5B2%5D%5Borderable%5D=true&columns%5B2%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B2%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B3%5D%5Bdata%5D=name&columns%5B3%5D%5Bname%5D=&columns%5B3%5D%5Bsearchable%5D=true&columns%5B3%5D%5Borderable%5D=true&columns%5B3%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B3%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B4%5D%5Bdata%5D=description&columns%5B4%5D%5Bname%5D=&columns%5B4%5D%5Bsearchable%5D=true&columns%5B4%5D%5Borderable%5D=true&columns%5B4%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B4%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B5%5D%5Bdata%5D=created_at&columns%5B5%5D%5Bname%5D=&columns%5B5%5D%5Bsearchable%5D=true&columns%5B5%5D%5Borderable%5D=true&columns%5B5%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B5%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B6%5D%5Bdata%5D=updated_at&columns%5B6%5D%5Bname%5D=&columns%5B6%5D%5Bsearchable%5D=true&columns%5B6%5D%5Borderable%5D=true&columns%5B6%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B6%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B7%5D%5Bdata%5D=id&columns%5B7%5D%5Bname%5D=&columns%5B7%5D%5Bsearchable%5D=true&columns%5B7%5D%5Borderable%5D=false&columns%5B7%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B7%5D%5Bsearch%5D%5Bregex%5D=false&order%5B0%5D%5Bcolumn%5D=6&order%5B0%5D%5Bdir%5D=desc&start=0&length=8&search%5Bvalue%5D=&search%5Bregex%5D=false';
        $permission = $this->call('POST', '/admin/permission/lists' . '?' . $params);
        $this->assertResponseOk();
    }

    public function testPostCreateGroup()
    {
        $permission = $this->post('/admin/permission/create', ['title' => 'test role', 'description' => 'test description', 'name' => 'test']);
        $this->assertRedirectedTo('/admin/permission/group');
    }

    public function testPostCreate()
    {
        $permission = Permission::orderBy('id', 'desc')->first();
        $permission = $this->post('/admin/permission/create', ['group_id' => $permission->id, 'title' => 'test role', 'description' => 'test description', 'name' => 'test']);
        $this->assertRedirectedTo('/admin/permission');
    }

    public function testGetEdit()
    {
        $permission = Permission::orderBy('id', 'desc')->first();
        $permission = $this->call('GET', '/admin/permission/edit/' . $permission->id);
        $this->assertResponseOk();
    }

    public function testPostEdit()
    {
        $permission = Permission::orderBy('id', 'desc')->first();
        $permission = $this->put('/admin/permission/edit/' . $permission->id, ['old_name' => $permission->name, 'name' => $permission->name, 'title' => 'test title update', 'description' => 'test description update']);
        $this->assertRedirectedTo('/admin/permission/group');
    }

    public function testDelete()
    {
        $permission = Permission::orderBy('id', 'desc')->first();
        $permission = $this->delete('/admin/permission/delete', ['ids' => [$permission->id]]);
        $this->assertResponseOk();
    }
}
