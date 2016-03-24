<?php

use App\Model\UserFields;
use Illuminate\Database\Seeder;

class UserFieldsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('user_fields')->delete();

        UserFields::create([
            'name' => 'position',
            'title' => '职位',
            'type' => 'text',
			'validation' => 'required',
			'description' => '职位',
        ]);
        UserFields::create([
            'name' => 'address',
            'title' => '地址',
            'type' => 'text',
			'validation' => '',
			'description' => '地址',
        ]);
        UserFields::create([
            'name' => 'department',
            'title' => '部门',
            'type' => 'text',
			'validation' => 'required',
			'description' => '部门',
        ]);
        UserFields::create([
            'name' => 'school',
            'title' => '学校',
            'type' => 'text',
			'validation' => 'required',
			'description' => '学校',
        ]);
        UserFields::create([
            'name' => 'sex',
            'title' => '性别',
            'type' => 'text',
			'validation' => 'in:男,女',
			'description' => '性别',
        ]);
    }
}
