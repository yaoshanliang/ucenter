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
                <a href="{{ URL('admin/user/all') }}" class="btn btn-primary">用户总库</a>
                &nbsp;
                <a href="{{ URL('admin/user/invite') }}" class="btn btn-primary">邀请用户</a>
                &nbsp;
                <a href='javascript:void(0);' class="btn btn-primary btn-danger" onclick='return check_remove();'>移除</a>
                <input type="text" id="search" class="form-control search" placeholder="搜索">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/app') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <table class="table table-striped table-bordered table-hover" id="user_index" class="display" cellspacing="0" width="100%" border='0px'>
                    <thead>
                        <tr>
                            <td style="width:15px"><input class="checkbox" type="checkbox" name="id" id='checkAll'></td>
                            <td>用户名</td>
                            <td>邮箱</td>
                            <td>手机</td>
                            <td>角色</td>
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
<div class="modal fade" id="choose_role_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:700px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title" id="myModalLabel">角色管理</h5>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover" id="role_index" class="display" cellspacing="0" width="100%" border='0px'>
                    <thead>
                        <tr>
                            <td style="width:15px"></td>
                            <td>名称</td>
                            <td>代号</td>
                            <td>描述</td>
                            <td>更新时间</td>
                        </tr>
                    </thead>
                    <tbody id="role_index_tbody">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script>
/*
var datatable_id = 'role_index';
var columnDefs_targets = [0];
var order = [4, 'desc'];
var ajax_url = '/admin/role/lists';
var remove_url = '/admin/user/remove';
var columns = [{
                    "data": "id",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html("<input type='checkbox' id='" + sData + "' class='checkbox' name='ids' value='" + sData + "'>");
                    }
                },
                {"data": "title"},
                {"data": "name"},
                {"data": "description"},
                {"data": "updated_at"},
                ];

if(typeof(datatable_id) != "undefined") {
var table;
$(document).ready(function() {
    datatable_base();
    // table = $('table.display').DataTable();
    table = $('#' + datatable_id).DataTable({
    // table = $('table.display').DataTable({
        //禁用排序列
        "columnDefs": [{
            "orderable": false,
            "targets": columnDefs_targets
        }],
        //默认排序列
        "order": order,
        "ajax": {
            "url": ajax_url,
            "type": 'POST',
            "dataType": 'json',
            "headers": {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
        },
        "columns": columns,
        "initComplete": initComplete
    });
    table.on( 'draw.dt', function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            increaseArea: '20%' // optional
        });
    });
    $("#choose_role_modal").on('hidden.bs.modal', function (table) {
        $('#' + 'user_index').DataTable().draw(false);
    })
});
}
*/
function choose_role(user_id, table) {
    // var ajax_url = '/admin/role/lists';
    // $("#role_index").DataTable().ajax.url(ajax_url).load();
    $.getJSON('/admin/user/' + user_id + '/roles', function(data) {
        if (data.code === 1) {
            data = data.data;
            console.log(nTr);
            for (var i = 0; i < data.length; i++) {
                console.log(data[i]);
            }
            var nTr = $("#role_index_tbody").append('11');
        }
    });
    $("#choose_role_modal").modal('show');
}
</script>
<script>
var datatable_id = 'user_index';
var columnDefs_targets = [0, 4, 6];
var order = [5, 'desc'];
var ajax_url = '/admin/user/lists';
var remove_url = '/admin/user/remove';
var columns = [{
                    "data": "id",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html("<input type='checkbox' id='" + sData + "' class='checkbox' name='ids' value='" + sData + "'>");
                    }
                },
                {"data": "username"},
                {"data": "email"},
                {"data": "phone"},
                {
                    "data": "roles",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        var roles = '';
                        $.each(sData, function() {
                            roles += this.title + ' | ';
                        });
                        roles = roles.substr(0, roles.length - 2);
                        $(nTd).html(roles);
                    }
                },
                {"data": "updated_at"},
                {
                    "data": "id",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html(
                            "<a href='javascript:void(0);' onclick='return choose_role(" + sData + ");'>角色</a>" + " " +
                            "<a href=/admin/user/" + sData + "/show>详细</a>" + " " +
                            "<a href=/admin/user/" + sData + "/edit>编辑</a>" + " " +
                            "<a href='javascript:void(0);' onclick='return check_remove(" + sData + ");'>移除</a>"
                        );
                    }
                }];
</script>
@endsection
