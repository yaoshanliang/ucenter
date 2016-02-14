@extends('admin.base')

@section('content')
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
    <div class="panel-heading">用户信息字段
        <div class="pull-right">
            <a href="/admin/user"><i class="fa fa-user"></i> 用 户 </a> /
            用户信息字段
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
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/app') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <table class="table table-striped table-bordered table-hover" id="user_fields" class="display" cellspacing="0" width="100%" border='0px'>
                    <thead>
                        <tr>
                            <td style="width:15px"><input class="checkbox" type="checkbox" name="id" id='checkAll'></td>
                            <td>名称</td>
                            <td>代号</td>
                            <td>类型</td>
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

@include('admin.partials.modal.role')

<script>
var datatable_id = 'user_fields';
var columnDefs_targets = [0, 7];
var order = [5, 'desc'];
var ajax_url = '/admin/user/fieldsLists';
var delete_url = '/admin/user/delete';
var columns = [{
                    "data": "id",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html("<input type='checkbox' id='" + sData + "' class='checkbox' name='ids' value='" + sData + "'>");
                    }
                },
                {"data": "title"},
                {"data": "name"},
                {"data": "type"},
                {"data": "description"},
                {"data": "created_at"},
                {"data": "updated_at"},
                {
                    "data": "id",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html(
                            "<a href='javascript:void(0);' onclick='return choose_role(" + sData + ");'>接入</a>"
                        );
                    }
                }];
</script>
@endsection
