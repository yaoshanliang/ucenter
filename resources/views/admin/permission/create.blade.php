@extends('admin.base')

@section('content')
<div class="panel panel-default">
	<div class="panel-heading">新增 接入权限</div>
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

		<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/permission') }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">

			<div class="form-group">
				<label class="col-md-3 control-label">代号</label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="name" value="{{ old('name') }}">
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">名称</label>
				<div class="col-md-6">
                    <input type="text" class="form-control" name="title" value="{{ old('title') }}">
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">描述</label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="description" value="{{ old('description') }}">
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-2 col-md-offset-3">
					<button type="submit" class="btn btn-primary">
						新增
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
