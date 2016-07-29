@extends('auth.base')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">登录</div>
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

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="col-md-3">
                        </div>

                        <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-2 control-label">账户</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="用户名/邮箱/手机">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">密码</label>
                            <div class="col-md-10">
                                <input type="password" class="form-control" name="password" placeholder="密码">
                            </div>
                        </div>

                        @if ($loginFailed)
                        <script src="//captcha.luosimao.com/static/js/api.js"></script>
                        <div class="form-group">
                            <label class="col-md-2 control-label">验证</label>
                            <div class="col-md-10">
                                <div class="l-captcha" data-site-key="{{ env('CAPTCHA_SITE_KEY') }}" data-width="100%"></div>
                            </div>
                        </div>
                        @endif

                        <div class="form-group">
                            <label class="col-md-2 control-label"></label>
                            <div class="col-md-10">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> 记住我
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label"></label>
                            <div class="col-md-10">
                                <button type="submit" class="btn btn-primary">登录</button>
                                <a class="btn btn-link" href="{{ url('/password/phone') }}">忘记密码</a>
                                <a class="btn btn-link" href="{{ url('/auth/register') }}">注册账户</a>
                                <img style="cursor:pointer; padding-left:20px;" src="{{ asset('/images/icon24_appwx_logo.png') }}"
                                    onclick="javascript:window.location.href='<?php echo
                                    "https://open.weixin.qq.com/connect/qrconnect?appid=" . env('WECHAT_APPID') .
                                    "&redirect_uri=" . urlencode(url('oauth/wechatCallback?goto=/auth/login')) .
                                    "&response_type=code&scope=snsapi_login&state=" . md5(time()) .
                                    "#wechat_redirect"; ?>'">
                            </div>
                            </div>
                        </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
