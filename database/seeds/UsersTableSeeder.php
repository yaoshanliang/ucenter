<?php

use App\Model\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->where('id', 1000)->delete();

        User::create([
            'id' => 1000,
            'username' => 'admin',
            'email' => 'i@iat.net.cn',
            'phone' => '18888888888',
			'password' => bcrypt('123456'),
        ]);
    }
}
