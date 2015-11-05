@extends('admin.base')

@section('content')
<div class="panel panel-default">
	<div class="panel-heading">编辑 用户</div>
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

	<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/app/'.$user->id) }}">
		<input name="_method" type="hidden" value="PUT">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="form-group">
				<label class="col-md-3 control-label">代号</label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="app" value="{{ $user->username }}">
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
                            <!-- Modal -->
                            <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                                        </div>
                                        <div class="modal-body">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- /.modal -->
