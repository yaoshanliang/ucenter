@extends('admin.base')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">编辑应用
        <div class="pull-right">
            <a href="/admin/app"><i class="fa fa-th"></i> 应 用</a> /
            编辑应用
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

    <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/app/'.$app->id) }}">
        <input name="_method" type="hidden" value="PUT">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="access_token" value="{{ $accessToken }}">

            <div class="form-group">
                <label class="col-md-3 control-label">名称</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="title" value="{{ $app->title }}">
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">描述</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="description" value="{{ $app->description }}">
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">首页地址</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="home_url" value="{{ $app->home_url }}">
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">登录地址</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="login_url" value="{{ $app->login_url }}">
                </div>
            </div>

            <div class="form-group"></div>

            <div class="form-group">
                <label class="col-md-3 control-label">Client_id</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="name" value="{{ $app->name }}">
                    <input type="hidden" class="form-control" name="old_name" value="{{ $app->name }}">
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">Client_secret</label>
                <div class="col-md-5">
                    <input type="text" class="form-control" name="secret" value="{{ $app->secret }}">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-default" onclick="generateSecret();">生成</button>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-2 col-md-offset-3">
                    <button type="submit" class="btn btn-primary">
                        编辑
                    </button>
                </div>
            </div>
        </form>

    </div>
    </div>
</div>
<script>
function generateSecret()
{
    $.ajax({
        url: '/api/app/secret',
        type: 'put',
        data: {'access_token': $('input[name="access_token"]').val()},
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        success: function(data) {
            if(data['code'] === 1) {
                showSuccessTip(data['message']);
                $("input[name='secret']").val(data['data']);
            } else {
                showFailTip(data['message']);
                return false;
            }
        },
        error: function(data) {
            showFailTip(data['message']);
            return false;
        },
    });
}
</script>
@endsection
