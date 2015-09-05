@extends('admin.base')

@section('content')
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
	<div class="panel-heading">用户管理</div>
	<div class="panel-body">
		<div class="dataTable_wrapper">
			<br />
			<div class="input-group custom-search-form">
				<a href="{{ URL('admin/user/create') }}" class="btn btn-primary">新增</a>
				&nbsp;
				<a href='javascript:void(0);' class="btn btn-primary btn-danger" onclick='return check_delete();'>删除</a>
				<input type="text" id="search" class="form-control search" placeholder="搜索">
				<span class="input-group-btn">
					<button class="btn btn-default" type="button">
						<i class="fa fa-search"></i>
					</button>
				</span>
			</div>
			<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/app') }}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="selected_ids" id="selected_ids">
				<table class="table table-striped table-bordered table-hover" id="dataTables-example" class="display" cellspacing="0" width="100%" border='0px'>
					<thead>
						<tr>
							<td style="width:15px"><input class="checkbox" type="checkbox" name="id" id='checkAll'></td>
							<td>用户名</td>
							<td>邮箱</td>
							<td>手机</td>
							<td>创建时间</td>
							<td>更新时间</td>
							<td>操作</td>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</form>
		</div>
		<!-- /.dataTable_wrapper -->
	</div>
	<!-- /.panel-body -->
</div>
<!-- /.panel -->
</div>
</div>
<!-- Modal -->
<div class="modal fade" id="confirm_delete_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title" id="myModalLabel">确定删除吗？</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" onClick="return submit_delete();">确定</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="no_selected_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title" id="myModalLabel">请先勾选数据</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">确定</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@endsection
