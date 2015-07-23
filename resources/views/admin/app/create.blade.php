@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">新增 接入应用</div>
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

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/app/store') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-md-4 control-label">代号</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="app" value="{{ old('app') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">名称</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="app_name" value="{{ old('app_name') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">首页地址</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="app_home_url" value="{{ old('app_home_url') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">登录地址</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="app_login_url" value="{{ old('app_login_url') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">密钥</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="app_secret" value="{{ old('app_secret') }}">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									新增
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
