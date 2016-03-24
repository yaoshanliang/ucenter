<?php

use App\Model\Setting;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('settings')->delete();

        Setting::create([
            'name' => 'site_name',
            'value' => '用户中心',
            'description' => '站点名称',
			'order' => 1,
            'type' => 'text',
        ]);

        Setting::create([
            'name' => 'site_url',
            'value' => 'http://ucenter.szjlxh.com',
            'description' => '站点网址',
			'order' => 2,
            'type' => 'text',
        ]);

        Setting::create([
			'name' => 'site_admin_name',
            'value' => '管理后台',
            'description' => '站点管理后台',
			'order' => 3,
            'type' => 'text',
        ]);

        Setting::create([
            'name' => 'site_description',
            'value' => '“统一身份认证”、“用户分离”、“权限分离”、“日志分离”',
            'description' => '站点描述',
			'order' => 4,
            'type' => 'text',
        ]);

        Setting::create([
            'name' => 'copyright',
            'value' => 'Copyright &copyright 2015-2016',
            'description' => '版权',
			'order' => 5,
            'type' => 'text',
        ]);

        Setting::create([
            'name' => 'support_email',
            'value' => 'support@iat.net.cn',
            'description' => '技术支持',
			'order' => 6,
            'type' => 'text',
        ]);

        Setting::create([
            'name' => 'bei_an',
            'value' => '',
            'description' => '站点备案号',
			'order' => 7,
            'type' => 'text',
        ]);

        Setting::create([
            'name' => 'tong_ji',
            'value' => '',
            'description' => '站点统计代码',
			'order' => 8,
            'type' => 'text',
        ]);

        Setting::create([
            'name' => 'page_size',
            'value' => '8',
            'description' => '列表每页显示数量',
			'order' => 9,
            'type' => 'text',
        ]);

        Setting::create([
            'name' => 'expire',
            'value' => '1',
            'description' => '缓存过期时间,单位分钟',
			'order' => 10,
            'type' => 'text',
        ]);

        Setting::create([
            'name' => 'register_on',
            'value' => '0',
            'description' => '用户注册开关,1 or 0',
			'order' => 11,
            'type' => 'text',
        ]);

        Setting::create([
            'name' => 'site_home_name',
            'value' => '个人中心',
            'description' => '站点个人中心',
			'order' => 3,
            'type' => 'text',
        ]);
    }
}
