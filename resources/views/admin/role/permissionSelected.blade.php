@extends('admin.base')

@section('content')
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
    <div class="panel-heading">权限管理&emsp;&emsp;
        当前角色:<span class="text-success">{{ $role->title }}</span>
        <div class="pull-right">
            <a href="/admin/role"><i class="fa fa-eye"></i> 角 色 </a> /
            <a href="/admin/role/<?php echo $role['id'];?>/permission">权限管理</a> /
            已拥有权限列表
        </div>
    </div>
    <div class="panel-body">
        <div class="dataTable_wrapper">
            <br />
            <div class="input-group custom-search-form">
                <input type="text" id="search" class="form-control search" placeholder="搜索">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/permission/app') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="selected_ids" id="selected_ids">
                <table class="table table-striped table-bordered table-hover" id="user_index" class="display" cellspacing="0" width="100%" border='0px'>
                    <thead>
                        <tr>
                            <td style="width:15px"><input class="checkbox" type="checkbox" name="id" id='checkAll'></td>
                            <td>分组</td>
                            <td>名称</td>
                            <td>代号</td>
                            <td>描述</td>
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

<script>
var datatable_id = 'user_index';
var columnDefs_targets = [0, 7];
var order = [6, 'desc'];
var ajax_url = '/admin/role/permissionselectedlists/<?php echo $role['id']; ?>';
// var delete_url = '/admin/role/<?php echo $role['id']; ?>/selectOrUnselectPermission/21?type=unselect';
var columns = [{
                    "data": "id",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html("<input type='checkbox' id='" + sData + "' class='checkbox' name='ids' value='" + sData + "'>");
                    }
                },
                {"data": "group_name"},
                {"data": "title"},
                {"data": "name"},
                {"data": "description"},
                {"data": "created_at"},
                {"data": "updated_at"},
                {
                    "data": "id",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html("");
                        // $(nTd).html("<a href=/admin/permission/" + sData + "/edit>编辑</a>" + " " +
                            // "<a href='javascript:void(0);' onclick='return check_remove(" + sData + ");'>移除</a>");
                    }
                }];
</script>
@endsection
