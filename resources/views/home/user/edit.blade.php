@extends('home.base')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">编辑个人信息
        <div class="pull-right">
            <a href="/home/user"><i class="fa fa-user"></i>个人信息</a> /
            编辑个人信息
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

    <form class="form-horizontal" role="form" method="POST" action="{{ url('/home/user/edit') }}">
        <input name="_method" type="hidden" value="PUT">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div>
        @foreach ($user['details'] as $k => $v)
            @if (@$i % 3 == 0)
                <div class="form-group">
            @endif
            <div class="col-md-4">
                <label class="col-md-3 control-label">{{ $v['title'] }}</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" name="{{ $k }}" value="{{ $v['value'] }}">
                </div>
            </div>
            @if (@$i++ % 3 == 2)
                </div>
            @endif
        @endforeach
        </div>
            <div class="form-group">
                <div class="col-md-4">
                    <div class="col-md-3"></div>
                    <div class="col-md-9">
                        <button type="submit" class="btn btn-primary">确认</button>
                        <button type="button" class="btn btn-primary pull-right">取消</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
    </div>
</div>
@endsection
