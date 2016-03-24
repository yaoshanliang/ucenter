<?php

use App\Model\Role;
use App\Model\UserRole;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        Role::where('app_id', 1)->delete();
        Role::whereIn('id', [1, 2])->delete();

        Role::create([
            'id' => 1,
            'app_id' => 1,
            'name' => 'common',
            'title' => '普通用户',
			'description' => '普通用户',
        ]);

        Role::create([
            'id' => 2,
            'app_id' => 1,
            'name' => 'developer',
            'title' => '开发者',
			'description' => '开发者',
        ]);

        UserRole::where('app_id', 1)->orWhere('user_id', 1000)->delete();
        UserRole::create([
            'user_id' => 1000,
            'app_id' => 1,
            'role_id' => 2
        ]);
    }
}
