<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li class="sidebar-search">
                <div class="input-group custom-search-form">
                    <input type="text" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
                    <button class="btn btn-default" type="button">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
                </div>
                <!-- /input-group -->
            </li>
            <li>
                <a href="/admin/index"><i class="fa fa-dashboard fa-fw"></i> 控制板</a>
            </li>
            <li>
                @if ($app['name'] == 'ucenter')
                    <a href="##"><i class="fa fa-bar-chart-o fa-fw"></i> 应 用<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="/admin/app/index"> 应用总库</a>
                        </li>
                        <li>
                            <a href="/admin/app/app"> 接入应用</a>
                        </li>
                    </ul>
                @else
                    <a href="/admin/app/app"><i class="fa fa-bar-chart-o fa-fw"></i> 应 用</a>
                @endif
            </li>
            <li>
                <a href="###"><i class="fa fa-book fa-fw"></i> 用 户<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="/admin/user/index"> 用户总库</a>
                    </li>
                    <li>
                        <a href="/admin/user/app"> 接入用户</a>
                    </li>
                </ul>
            </li>
            <li>
                @if ($app['name'] == 'ucenter')
                    <a href="###"><i class="fa fa-bar-chart-o fa-fw"></i> 角 色<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="/admin/role/index"> 角色总库</a>
                        </li>
                        <li>
                            <a href="/admin/role/app"> 接入角色</a>
                        </li>
                    </ul>
                @else
                    <a href="/admin/role/app"><i class="fa fa-bar-chart-o fa-fw"></i> 角 色</a>
                @endif
            </li>
            <li>
                @if ($app['name'] == 'ucenter')
                    <a href="####"><i class="fa fa-bar-chart-o fa-fw"></i> 权 限<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="/admin/permission/index"> 权限总库</a>
                        </li>
                        <li>
                            <a href="/admin/permission/app"> 接入权限</a>
                        </li>
                    </ul>
                @else
                    <a href="/admin/permission/app"><i class="fa fa-bar-chart-o fa-fw"></i> 权 限</a>
                @endif
            </li>
            <li>
                <a href="/admin/file"><i class="fa fa-tags fa-fw"></i> 文 件</a>
            </li>
            <li>
                <a href="/admin/mail"><i class="fa fa-tags fa-fw"></i> 邮 件</a>
            </li>
            <li>
                <a href="/admin/message"><i class="fa fa-tags fa-fw"></i> 短 信</a>
            </li>
            <li>
                <a href="/admin/log"><i class="fa fa-cog fa-fw"></i> 日 志</a>
            </li>
        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>
<!-- /.navbar-static-side -->
