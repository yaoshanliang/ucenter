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

                    <form method="post" action="{{ route('oauth.authorize.post', $params) }}">
                        <input type="hidden" name="client_id" value="{{$params['client_id']}}">
                        <input type="hidden" name="redirect_uri" value="{{$params['redirect_uri']}}">
                        <input type="hidden" name="response_type" value="{{$params['response_type']}}">
                        <input type="hidden" name="state" value="{{$params['state']}}">
                        <input type="hidden" name="scope" value="{{$params['scope']}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="col-md-4">
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <p class="lead">{{ $client->getName() }}</p>
                                将访问您的如下资料：
                                <ul>
                                <li class="text-success">个人信息</li>
                                </ul>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="approve" value="1" class="btn btn-primary">授权</button>
                                <button type="submit" name="deny" value="1" class="btn btn-primary">取消</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
