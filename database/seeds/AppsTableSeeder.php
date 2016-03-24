<?php

use App\Model\App;
use Illuminate\Database\Seeder;

class AppsTableSeeder extends Seeder
{
    public function run()
    {
        App::where('name', 'ucenter')->orWhere('id', 1)->delete();
        App::create([
            'id' => 1,
            'name' => 'ucenter',
            'title' => '用户中心',
            'home_url' => 'http://ucenter.szjlxh.com',
            'login_url' => 'http://ucenter.szjlxh.com/auth/login',
            'description' => '用户中心',
            'user_id' => 1000,
        ]);

        DB::table('oauth_clients')->where('id', 'ucenter')->delete();
        DB::table('oauth_clients')->insert([
            'id' => 'ucenter',
            'secret' => 'ucenter_secret',
            'name' => '用户中心'
        ]);

        DB::table('oauth_client_endpoints')->where('client_id', 'ucenter')->delete();
        DB::table('oauth_client_endpoints')->insert([
            'client_id' => 'ucenter',
            'redirect_uri' => 'http://ucenter.szjlxh.com/auth/login',
        ]);
    }
}
