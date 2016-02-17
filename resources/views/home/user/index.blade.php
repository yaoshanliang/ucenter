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
function confirmEditUsername() {
    var username = $('input[name="username"]').val();
    if (username.length == 0) {
        showFailTip('请输入新用户名');
        return false;
    }
    $.ajax({
        url: '/api/user/edit',
        data: {'username': username, 'access_token': 'test'},
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
function confirmEditEmail() {
    var email = $('input[name="email"]').val();
    if (email.length == 0) {
        showFailTip('请输入新邮箱');
        return false;
    }
    $.ajax({
        url: '/api/user/edit',
        data: {'email': email, 'access_token': 'test'},
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
function sendCode() {
    var phone = $('input[name="phone"]').val();
    if (phone.length != 11) {
        showFailTip('手机号不合法');
        return false;
    }

    $.ajax({
        url: '/api/sms/send_code',
        data: {'phone': phone, 'access_token': 'test'},
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        success: function(data) {
            if(data['code'] === 1) {
                showSuccessTip(data['message']);
                var InterValObj;
                var total_count = 60;//总倒计时秒数
                var current_count;
                current_count = total_count;
                $("#send_code").val(current_count + "(s)后重发");
                InterValObj = window.setInterval(setRemainTime, 1000);
                $("#send_code").addClass('disabled');
                $("#send_code").removeClass('btn-outline');
                //计时器
                function setRemainTime() {
                    if (current_count == 0) {
                        window.clearInterval(InterValObj);
                        $("#send_code").removeClass("disabled");
                        $("#send_code").addClass('btn-outline');
                        $("#send_code").val("发送验证码");
                    } else {
                        current_count--;
                        $("#send_code").val(current_count + "(s)后重发");
                    }
                }

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

function validateCode() {
    var phone = $('input[name="phone"]').val();
    if (phone.length != 11) {
        showFailTip('手机号不合法');
        return false;
    }
    var code = $('input[name="code"]').val();
    if (code.length != 6) {
        showFailTip('验证码不合法');
        return false;
    }
    $.ajax({
        url: url,
        data: {'phone': phone, 'code': code},
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        success: function(data) {
            if(data['code'] === 1) {
                showSuccessTip(data['message']);
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
                            <button type="button" class="btn btn-primary btn-block" onClick="return confirmEditUsername();">确认</button>
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
                            <button type="button" class="btn btn-primary btn-block" onClick="return confirmEditEmail();">确认</button>
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
@endsection
