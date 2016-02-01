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
                <a href="/admin/index"><i class="fa fa-home fa-fw"></i> 控制板</a>
            </li>

            @if ((Session::get('current_role'))['name'] == 'developer')
                <li>
                    <a href="/admin/app"><i class="fa fa-th fa-fw"></i> 应 用</a>
                </li>
            @endif

            <li>
                <a href="/admin/user"><i class="fa fa-user fa-fw"></i> 用 户</a>
            </li>
            <li>
                <a href="/admin/role"><i class="fa fa-eye fa-fw"></i> 角 色</a>
            </li>
            <li>
                <a href="/admin/permission"><i class="fa fa-key fa-fw"></i> 权 限</a>
            </li>
            <li>
                <a href="/admin/file"><i class="fa fa-file-o fa-fw"></i> 文 件</a>
            </li>
            <li>
                <a href="/admin/mail"><i class="fa fa-envelope-o fa-fw"></i> 邮 件</a>
            </li>
            <li>
                <a href="/admin/message"><i class="fa fa-comments-o fa-fw"></i> 短 信</a>
            </li>
            <li>
                <a href="/admin/userlog"><i class="fa fa-file-text-o fa-fw"></i> 日 志</a>
            </li>
        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>
<!-- /.navbar-static-side -->
