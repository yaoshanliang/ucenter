@extends('admin.base')

@section('content')
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
	<div class="panel-heading">用户管理</div>
	<div class="panel-body">
		<div class="dataTable_wrapper">
				<br />
				<table class="table table-striped table-bordered table-hover" id="dataTables-example" class="display" cellspacing="0" width="100%" border='0px'>
					<thead>
						<tr>
							<td>用户名</td>
							<td>邮箱</td>
							<td>手机</td>
							<td>角色</td>
							<td>编辑</td>
							<td>删除</td>
						</tr>
					</thead>
					<tbody>
					@foreach ($users as $user)
						<tr>
							<td>
								{{ $user->username }}
							</td>
							<td>
								{{ $user->email }}
							</td>
							<td>
								{{ $user->phone }}
							</td>
							<td>
							</td>
							<td>
							</td>
							<td>
							</td>
							</tr>
					@endforeach
					</tbody>
				</table>
		</div>
		<!-- /.dataTable_wrapper -->
	</div>
	<!-- /.panel-body -->
</div>
<!-- /.panel -->
</div>
</div>
@endsection
