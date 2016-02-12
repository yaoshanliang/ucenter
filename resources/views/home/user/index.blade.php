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

    <form class="form-horizontal" role="form" method="POST" action="">
        <input name="_method" type="hidden" value="PUT">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <div class="col-md-6">
                <label class="col-md-3 control-label">用户名</label>
                <div class="col-md-3">
                    <p class="form-control-static">{{ $user->username }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <label class="col-md-3 control-label">用户名</label>
                <div class="col-md-3">
                    <p class="form-control-static">{{ $user->username }}</p>
                </div>
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
