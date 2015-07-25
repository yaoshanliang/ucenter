@extends('admin.base')

@section('content')
<div class="panel panel-default">
	<div class="panel-heading">编辑 接入应用</div>
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
			<div class="form-group">
				<label class="col-md-3 control-label">代号</label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="app" value="{{ $app->app }}">
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">名称</label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="app_name" value="{{ $app->app_name }}">
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">首页地址</label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="app_home_url" value="{{ $app->app_home_url }}">
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">登录地址</label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="app_login_url" value="{{ $app->app_login_url }}">
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">密钥</label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="app_secret" value="{{ $app->app_secret }}">
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
@endsection
