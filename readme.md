#用户中心
####——“统一身份认证服务”、“用户系统”、“权限系统”、“日志系统”、“文件系统”、“邮件服务”、“短信服务”
* [Laravel](http://laravel.com)
* [Bootstrap](http://getbootstrap.com)
* [Redis](http://redis.io)
* [DataTables](http://datatables.net)
* [monolog](https://github.com/yaoshanliang/monolog)
* [entrust](https://github.com/yaoshanliang/entrust)

##安装配置

####一些权限

* chmod -R 777 storage
* chmod -R 777 bootstrap/cache


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






