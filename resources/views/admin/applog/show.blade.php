@extends('admin.base')

@section('content')
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
    <div class="panel-heading">用户日志
        <div class="pull-right">
            <a href="/admin/applog"><i class="fa fa-user"></i> 日 志 </a> /
            详细日志
        </div>
    </div>
    <div class="panel-body">
    <form class="form-horizontal" role="form" method="POST" action="">
        <input name="_method" type="hidden" value="PUT">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <div class="col-md-4">
                <label class="col-md-4 control-label">用户名</label>
                <p class="form-control-static">{{ $user['username'] }}</p>
            </div>
            <div class="col-md-4">
                <label class="col-md-4 control-label">邮箱</label>
                <p class="form-control-static">{{ $user['email'] }}</p>
            </div>
            <div class="col-md-4">
                <label class="col-md-4 control-label">手机</label>
                <p class="form-control-static">{{ $user['phone'] }}</p>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-4">
                <label class="col-md-4 control-label">类型</label>
                <p class="form-control-static">{{ $applog['type'] }}</p>
            </div>
            <div class="col-md-4">
                <label class="col-md-4 control-label">标题</label>
                <p class="form-control-static">{{ $applog['title'] }}</p>
            </div>
            <div class="col-md-4">
                <label class="col-md-4 control-label">IP</label>
                <p class="form-control-static">{{ $applog['ip'] }}</p>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-4">
                <label class="col-md-4 control-label">触发时间</label>
                <p class="form-control-static">{{ $applog['pushed_at'] }}</p>
            </div>
            <div class="col-md-4">
                <label class="col-md-4 control-label">执行时间</label>
                <p class="form-control-static">{{ $applog['poped_at'] }}</p>
            </div>
            <div class="col-md-4">
                <label class="col-md-4 control-label">写库时间</label>
                <p class="form-control-static">{{ $applog['created_at'] }}</p>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-4">
                <label class="col-md-4 control-label">数据</label>
                <p class="form-control-static">{{ $applog['data'] }}</p>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-4">
                <label class="col-md-4 control-label">SQL</label>
                <p class="form-control-static">{{ $applog['sql'] }}</p>
            </div>
        </div>
    </form>
    </div>
    <!-- /.panel-body -->
</div>
<!-- /.panel -->
</div>
</div>
@endsection
