@extends('home.base')

@section('content')
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
    <div class="panel-heading">个人信息
        <div class="pull-right">
            <i class="fa fa-user"></i>个人信息
        </div>
    </div>
    <div class="panel-body">
    <form class="form-horizontal" role="form" method="POST" action="">
        <input name="_method" type="hidden" value="PUT">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <div class="col-md-4">
                <label class="col-md-3 control-label">用户名</label>
                <p class="form-control-static">{{ $user['username'] }}</p>
            </div>
            <div class="col-md-4">
                <label class="col-md-3 control-label">邮箱</label>
                <p class="form-control-static">{{ $user['email'] }}</p>
            </div>
            <div class="col-md-4">
                <label class="col-md-3 control-label">手机</label>
                <p class="form-control-static">{{ $user['phone'] }}
                    <button type="button" class="btn btn-outline btn-primary btn-xs" onclick="bindPhone();">
                        @if (empty($user['phone']))
                            绑定
                        @else
                            修改
                        @endif
                    </button>
                </p>
            </div>
        </div>
        @foreach ($user['details'] as $v)
            @if (@$i % 3 == 0)
                <div class="form-group">
            @endif
            <div class="col-md-4">
                <label class="col-md-3 control-label">{{ $v['title'] }}</label>
                <p class="form-control-static">{{ $v['value'] }}</p>
            </div>
            @if (@$i++ % 3 == 2)
                </div>
            @endif
        @endforeach
    </form>
    </div>
    <!-- /.panel-body -->
</div>
<!-- /.panel -->
</div>
</div>
<script>
function bindPhone() {
    $('#bind_phone').modal('show');
}
</script>
<!-- Modal -->
<div class="modal fade" id="bind_phone" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:400px; margin-top:40px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title" id="myModalLabel">绑定手机</h5>
            </div>
            <div class="modal-body">
            <form class="form-horizontal">
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="text" class="form-control" name="phone" placeholder="手机号">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="code" placeholder="验证码">
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-outline btn-success">发送验证码</button>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary btn-block">确认</button>
                    </div>
                </div>
            </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
</script>
@endsection
