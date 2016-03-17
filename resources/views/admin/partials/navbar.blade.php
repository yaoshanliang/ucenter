<div class="navbar-header">
    <a class="navbar-brand" href={{ Cache::get(Config::get('cache.settings') . 'site_url') }}>{{ Cache::get(Config::get('cache.settings') . 'site_name') }}</a>
    <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
            <a  href="#" class="dropdown-toggle navbar-brand" data-toggle="dropdown">{{ Cache::get(Config::get('cache.settings') . 'site_admin_name') }}<span class="caret"></span></a>
            <ul class="dropdown-menu" style="min-width: 100%;" role="menu">
                <li><a  href={{ Cache::get(Config::get('cache.settings') . 'site_url') }}/home>{{ Cache::get(Config::get('cache.settings') . 'site_home_name') }}</a></li>
            </ul>
        </li>
    </ul>
</div>
<!-- /.navbar-header -->

<ul class="nav navbar-nav navbar-right">
    <li class="dropdown">
        <a  href="#" class="dropdown-toggle" data-toggle="dropdown">{{ Session::get('current_app_title') }}<span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
                @foreach (Session::get('apps') as $app)
                    <li><a  href="javascript:void(0);" onclick="change_app('/home/app/currentApp', {{ $app['id'] }});">{{ $app['title'] }}</a></li>
                    <li class="divider"></li>
                @endforeach
            </ul>
    </li>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ Session::get('current_role_title') }} <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
                @foreach (Session::get('roles.' . Session::get('current_app_id')) as $role)
                    <li><a href="javascript:void(0);" onclick="change_role('/home/app/currentRole', {{ $role['id'] }})">{{ $role['title'] }}</a></li>
                    <li class="divider"></li>
                @endforeach
            </ul>
    </li>
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>
        </a>
        <ul class="dropdown-menu dropdown-alerts">
            <li>
                <a href="javascript:void(0);">
                    <div>
                        <i class="fa fa-comment fa-fw"></i>通知
                        <span class="pull-right text-muted small">4 分钟前</span>
                    </div>
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a class="text-center" href="#">所有通知
                    <i class="fa fa-angle-right"></i>
                </a>
            </li>
        </ul>
        <!-- /.dropdown-alerts -->
    </li>
    <!-- /.dropdown -->
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
        </a>
        <ul class="dropdown-menu dropdown-user">
            <li>
                <a href="/home/user"><i class="fa fa-user fa-fw"></i>个人信息</a>
            </li>
            <li class="divider"></li>
            <li>
                <a href="/auth/logout"><i class="fa fa-sign-out fa-fw"></i>退出</a>
            </li>
        </ul>
        <!-- /.dropdown-user -->
    </li>
    <!-- /.dropdown -->
</ul>
<!-- /.navbar-top-links -->
