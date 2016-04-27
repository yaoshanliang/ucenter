@extends('home.base')

@section('content')
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
    <div class="panel-heading">基本信息
        <div class="pull-right">
            <i class="fa fa-user"></i>个人信息
        </div>
    </div>
    <div class="panel-body">
    <form class="form-horizontal" role="form" method="POST" action="">
        <input name="_method" type="hidden" value="PUT">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="access_token" value="{{ $accessToken }}">

        <div class="form-group">
            <div class="col-md-4">
                <label class="col-md-3 control-label">用户名</label>
                <p class="form-control-static">{{ $user['username'] }}
                    <button type="button" class="btn btn-outline btn-primary btn-xs" onclick="editUsername();">修改</button>
                </p>
            </div>
            <div class="col-md-4">
                <label class="col-md-3 control-label">邮箱</label>
                <p class="form-control-static">{{ $user['email'] }}
                    <button type="button" class="btn btn-outline btn-primary btn-xs" onclick="editEmail();">
                        @if (empty($user['email']))
                            绑定
                        @else
                            修改
                        @endif
                    </button>
                </p>
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
        <div class="form-group">
            <div class="col-md-4">
                <label class="col-md-3 control-label">微信</label>
                <p class="form-control-static">{{ $wechat['nickname'] }}
                    <button type="button" class="btn btn-outline btn-primary btn-xs" onclick="bindWechat();">
                        @if (empty($wechat))
                            绑定
                        @else
                            修改
                        @endif
                    </button>
                </p>
            </div>
            <div class="col-md-4">
                <label class="col-md-3 control-label">密码</label>
                <p class="form-control-static">*********
                    <button type="button" class="btn btn-outline btn-primary btn-xs" onclick="resetPassword();">
                            修改
                    </button>
                </p>
            </div>
        </div>
    </form>
    </div>
    <!-- /.panel-body -->
</div>
<!-- /.panel -->
<div class="panel panel-default">
    <div class="panel-heading">详细信息
        <a href="/home/user/edit">编辑</a>
        <div class="pull-right">
            <i class="fa fa-user"></i>个人信息
        </div>
    </div>
    <div class="panel-body">
    <form class="form-horizontal" role="form" method="POST" action="">
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
    </div>
</div>
</div>
</div>
<script src="{{ asset('/js/sms.js') }}"></script>
<script>
function editUsername() {
    $('#edit_username').modal('show');
}
function editEmail() {
    $('#edit_email').modal('show');
}
function bindPhone() {
    $('#bind_phone').modal('show');
}
function bindWechat() {
    $.getScript('http://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js',function(){
        var obj = new WxLogin({
            id: "wechat_container",
            appid: "<?php echo env('WECHAT_APPID'); ?>",
            scope: "snsapi_login, snsapi_userinfo",
            redirect_uri: "<?php echo urlencode(url('home/user/wechatcallback')); ?>",
            state: "<?php echo md5(time()); ?>",
            style: "",
            href: ""
        });
    });
    $('#bind_wechat').modal('show');
}
function resetPassword() {
    $('#reset_password').modal('show');
}
function confirmEdit(field) {
    switch (field) {
        case 'username' :
            var value = $('input[name="username"]').val();
            if (value.length == 0) {
                showFailTip('请输入新用户名');
                return false;
            }
        break;
        case 'email' :
            var value = $('input[name="email"]').val();
            if (value.length == 0) {
                showFailTip('请输入新邮箱');
                return false;
            }
        break;
        case 'phone' :
            var value = $('input[name="phone"]').val();
            if (value.length == 0) {
                showFailTip('请输入新手机号');
                return false;
            }
        break;
        case 'password' :
            var value = $('input[name="password"]').val();
            var confirm_password = $('input[name="comfirm_password"]').val();
            if (value.length == 0 || confirm_password.length === 0) {
                showFailTip('请输入密码');
                return false;
            }
            if (value != confirm_password) {
                showFailTip('两次密码不一致');
                return false;
            }
        break;
    }
    var data = {};
    data[field] = value;
    data['access_token'] = "<?php echo $accessToken; ?>";
    $.ajax({
        url: '/api/user',
        type: 'PUT',
        data: data,
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        success: function(data) {
            if(data['code'] === 1) {
                showSuccessTip(data['message']);
                window.location.reload();
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
<!-- Modal -->
<div class="modal fade" id="edit_username" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:400px; margin-top:40px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title" id="myModalLabel">修改用户名</h5>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="username" placeholder="新用户名">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary btn-block" onClick="return confirmEdit('username');">确认</button>
                        </div>
                    </div>
                </div>
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

<!-- Modal -->
<div class="modal fade" id="edit_email" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:400px; margin-top:40px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title" id="myModalLabel">修改邮箱</h5>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="email" placeholder="新邮箱">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary btn-block" onClick="return confirmEdit('email');">确认</button>
                        </div>
                    </div>
                </div>
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

<!-- Modal -->
<div class="modal fade" id="bind_phone" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:400px; margin-top:40px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title" id="myModalLabel">绑定手机</h5>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
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
                            <input type="button" id="send_code" class="btn btn-outline btn-success" onClick="return sendCode();" value="发送验证码">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary btn-block" onClick="return validateCode();">确认</button>
                        </div>
                    </div>
                </div>
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

<!-- Modal -->
<div class="modal fade" id="bind_wechat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:600px; margin-top:40px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title" id="myModalLabel">绑定微信</h5>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-offset-3">
                            <div id="wechat_container"></div>
                        </div>
                    </div>
                </div>
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

<!-- Modal -->
<div class="modal fade" id="reset_password" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:400px; margin-top:40px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title" id="myModalLabel">修改密码</h5>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="password" class="form-control" name="password" placeholder="新密码">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="password" class="form-control" name="comfirm_password" placeholder="确认新密码">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary btn-block" onClick="return confirmEdit('password');">确认</button>
                        </div>
                    </div>
                </div>
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
@endsection
