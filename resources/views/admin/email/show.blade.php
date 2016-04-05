@extends('admin.base')

@section('content')
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
    <div class="panel-heading">邮件详细
        <div class="pull-right">
            <a href="/admin/user"><i class="fa fa-user"></i> 邮 件 </a> /
            邮件详细
        </div>
    </div>
    <div class="panel-body">
    <form class="form-horizontal" role="form" method="POST" action="">
        <input name="_method" type="hidden" value="PUT">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <div class="col-md-4">
                <label class="col-md-3 control-label">收件人</label>
                <p class="form-control-static">{{ $email['email'] }}</p>
            </div>
            <div class="col-md-4">
                <label class="col-md-3 control-label">主题</label>
                <p class="form-control-static">{{ $email['subject'] }}</p>
            </div>
            <div class="col-md-4">
                <label class="col-md-3 control-label">发件人</label>
                <p class="form-control-static">{{ $email['user_id'] }}</p>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-offset-1">
                {!! $email['content'] !!}
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
