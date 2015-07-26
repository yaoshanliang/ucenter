@extends('admin.base')

@section('content')
<div class="panel panel-default">
	<div class="panel-heading">应用管理</div>
	<div class="panel-body">
		<a href="{{ URL('admin/app/create') }}" class="btn btn-primary">新增</a>
		<br />
		<br />
		<table class="table table-striped">
			<tr class="row">
				<th class="col-lg-2">应用代号</th>
				<th class="col-lg-2">应用名称</th>
				<th class="col-lg-3">首页地址</th>
				<th class="col-lg-3">登录地址</th>
				<th class="col-lg-1">编辑</th>
				<th class="col-lg-1">删除</th>
			</tr>
			@foreach ($apps as $app)
            <tr class="row">
				<td class="col-lg-1">
					{{ $app->app }}
				</td>
				<td class="col-lg-2">
					{{ $app->app_name }}
				</td>
				<td class="col-lg-2">
					<a href="{{ $app->app_home_url }}" target="_blank">{{ $app->app_home_url }}</a>
				</td>
				<td class="col-lg-2">
					{{ $app->app_login_url }}
				</td>
				<td class="col-lg-1">
					<a href="{{ URL('admin/app/'.$app->id.'/edit') }}" class="btn btn-success">编辑</a>
				</td>
				<td class="col-lg-1">
					<form action="{{ URL('admin/app/'.$app->id) }}" method="POST" style="display: inline;">
						<input name="_method" type="hidden" value="DELETE">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<button type="submit" class="btn btn-danger" onclick="return confirm('确定删除?')">删除</button>
						</form>
				</td>
			</tr>
			@endforeach
		</table>
		<?php echo $apps->render(); ?>
	</div>
	</div>
</div>
@endsection
