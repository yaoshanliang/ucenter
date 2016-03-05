@extends('admin.base')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">新增应用
        <div class="pull-right">
            <a href="/admin/app"><i class="fa fa-th"></i> 应 用</a> /
            新增应用
        </div>
    </div>
    <div class="panel-body">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/app/create') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group">
                <label class="col-md-3 control-label">名称</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="title" value="{{ old('title') }}" placeholder="例:用户中心">
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">描述</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="description" value="{{ old('description') }}" placeholder="例:用户统一管理开发者平台">
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">首页地址</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="home_url" value="{{ old('home_url') }}" placeholder="例:https://ucenter.szjlxh.com">
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">回调地址</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="login_url" value="{{ old('login_url') }}" placeholder="例:https://ucenter.szjlxh.com/auth/login">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-3 control-label">
                    <input type="checkbox" name="role" checked="checked"></input>
                </div>
                <label class="col-md-5" style="padding-top:7px; font-weight:100">创建访客(guest)角色</label>
            </div>

            <div class="form-group">
                <div class="col-md-2 col-md-offset-3">
                    <button type="submit" class="btn btn-primary">
                        新增
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
