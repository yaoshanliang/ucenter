@extends('oauth.base')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">授权</div>
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

                    <form class="form-horizontal" method="post" role="form" action="{{ route('oauth.authorize.post', $params) }}">
                        <input type="hidden" name="client_id" value="{{$params['client_id']}}">
                        <input type="hidden" name="redirect_uri" value="{{$params['redirect_uri']}}">
                        <input type="hidden" name="response_type" value="{{$params['response_type']}}">
                        <input type="hidden" name="state" value="{{$params['state']}}">
                        <input type="hidden" name="scope" value="{{$params['scope']}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="col-md-4">
                            <div class="form-group">
                                <p class="lead">{{ $client->getName() }}</p>
                                将访问您的如下资料：
                                <ul>
                                <li class="text-success">个人信息</li>
                                </ul>
                            </div>
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

                            <div class="form-group">
                                <label class="col-md-2 control-label"></label>
                                <div class="col-md-10">
                                    <button type="submit" name="approve" value=1 class="btn btn-primary">登录</button>
                                    <button type="submit" name="approve" value=0 class="btn btn-primary">取消</button>
                                </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label"></label>
                                <div class="col-md-10">
                                    <a class="btn btn-link" href="{{ url('/password/phone') }}">忘记密码</a>
                                    <a class="btn btn-link" href="{{ url('/auth/register') }}">注册账户</a>
                                    <img style="cursor:pointer; padding-left:20px;" src="{{ asset('/images/icon24_appwx_logo.png') }}"
                                        onclick="javascript:window.location.href='<?php echo
                                        "https://open.weixin.qq.com/connect/qrconnect?appid=" . env('WECHAT_APPID') .
                                        "&redirect_uri=" . urlencode(url('oauth/wechatCallback')) .
                                        "&goto=" . urlencode($_GET['redirect_uri']) .
                                        "&response_type=code&scope=snsapi_login&state=" . md5(time()) .
                                        "#wechat_redirect"; ?>'">
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
