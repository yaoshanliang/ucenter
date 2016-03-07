@extends('admin.base')

@section('content')
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
    <div class="panel-heading">用户日志</div>
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
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/userlog') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <table class="table table-striped table-bordered table-hover" id="user_index" class="display" cellspacing="0" width="100%" border='0px'>
                    <thead>
                        <tr>
                            <td>用户名</td>
                            <td>类型</td>
                            <td>说明</td>
                            <td>数据</td>
                            <td>ip</td>
                            <td>触发时间</td>
                            <td>写库时间</td>
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
var columnDefs_targets = [7];
var order = [5, 'desc'];
var ajax_url = '/admin/applog/lists';
var delete_url = '/admin/user/delete';
var columns = [
                {"data": "username"},
                {"data": "type"},
                {
                    "data": "title",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        if (sData.length > 8) {
                            sData = sData.substr(0, 8) + '...';
                        }
                        $(nTd).html(sData);
                    }
                },
                {
                    "data": "data",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        if (sData.length > 10) {
                            sData = sData.substr(0, 10) + '...';
                        }
                        $(nTd).html(sData);
                    }
                },
                {"data": "ip"},
                {"data": "pushed_at"},
                {"data": "created_at"},
                {
                    "data": "id",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html("<a href=/admin/applog/show/" + sData + ">详细</a>");
                    }
                }];
</script>
@endsection
