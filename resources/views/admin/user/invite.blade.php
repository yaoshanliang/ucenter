@extends('admin.base')

@section('content')
<div class="panel panel-default">
	<div class="panel-heading">邀请 用户</div>
	<div class="panel-body">
        @include('errors.list')

		<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/user/invite') }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">

			<div class="form-group">
				<label class="col-md-3 control-label">邮箱</label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="email" value="{{ old('email') }}">
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-2 col-md-offset-3">
					<button type="submit" class="btn btn-primary">
						邀请加入
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
