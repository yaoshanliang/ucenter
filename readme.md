#用户中心
####——“统一身份认证服务”、“用户系统”、“权限系统”、“日志系统”、“文件系统”、“邮件服务”、“短信服务”
*演示地址: [http://demo.ucenter.szjlxh.com](http://demo.ucenter.szjlxh.com)*

*开放平台: [http://yaoshanliang.github.io/ucenter-open](http://yaoshanliang.github.io/ucenter-open)*

* [Laravel](http://laravel.com)
* [Bootstrap](http://getbootstrap.com)
* [Redis](http://redis.io)
* [DataTables](http://datatables.net)
* [phpsms](https://github.com/yaoshanliang/phpsms)
* [dingo/api](https://github.com/dingo/api)

##安装配置

1、下载源码
```
git clone https://github.com/yaoshanliang/ucenter.git
```
或者
```
composer require yaoshanliang/ucenter
```

2、一些权限
```
chmod -R 777 storage
chmod -R 777 bootstrap/cache
```

3、安装依赖
```
composer install
```

4、配置
```
cp .env.example .env
```
修改为自己的配置

5、执行数据库迁移
```
php artisan migrate
```

6、导入数据
```
php artisan db:seed
```

##Q&A

####1、开发者开发的流程？

* （1）创建应用
* （2）调用API进行开发
* （3）用户分配
* （4）权限分配
* （5）应用上线


####2、应用中的用户怎么来？

* 自行注册，申请某个应用的访问权限，待应用管理员允许后方可登陆访问；
* 应用管理员从用户库中选择可以访问的用户，用户库中不存在时可添加(导入)用户；


####3、应用中的用户权限怎么管理？

* 应用管理员在用户中心的后台管理中选择可访问的用户，分配相应的角色。

####4、一个用户可以多个角色吗？

* 可以。可以在应用中进行角色的切换，应用管理员需设置用户的默认角色；如：应用开发者也是应用管理员的角色。




