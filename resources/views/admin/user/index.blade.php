@extends('admin.base')

@section('content')
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
    <div class="panel-heading">用户管理
        <div class="pull-right">
            <i class="fa fa-user"></i> 用 户
        </div>
    </div>
    <div class="panel-body">
        <div class="dataTable_wrapper">
            <br />
            <div class="input-group custom-search-form">
                <a href="{{ URL('admin/user/all') }}" class="btn btn-primary">用户总库</a>
                &nbsp;
                <a href="{{ URL('admin/user/access') }}" class="btn btn-primary">处理申请</a>
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

@include('admin.partials.modal.remove')
@include('admin.partials.modal.role')

<script>
function choose_role(user_id) {
    $.getJSON('/admin/user/role/' + user_id, function(data) {
        if (data.code === 0) {
            data = data.data;
            var html;
            for (var i = 0; i < data.length; i++) {
                html += '<tr>';
                if (data[i].checked) {
                    html += '<td><input class="checkbox" type="checkbox" name="id" checked="checked" value=' + data[i].id + '></input></td>';
                } else {
                    html += '<td><input class="checkbox" type="checkbox" name="id" value="' + data[i].id + '"></input></td>';
                }
                html += '<td>' + data[i].title + '</td>';
                html += '<td>' + data[i].name + '</td>';
                html += '<td>' + data[i].description + '</td>';
                html += '<td>' + data[i].updated_at + '</td>';
            }
            var nTr = $("#role_index_tbody").html(html);
        }
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            increaseArea: '20%' // optional
        });
        $('input').on('ifChecked', function(event){
            selectOrUnselectRole(user_id, $(this).val())
        });
        $('input').on('ifUnchecked', function(event){
            selectOrUnselectRole(user_id, $(this).val())
        });
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
                    "data": "app_roles",
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
                            "<a href=/admin/user/show/" + sData + ">详细</a>" + " " +
                            // "<a href=/admin/user/" + sData + "/edit>编辑</a>" + " " +
                            "<a href='javascript:void(0);' onclick='return check_remove(" + sData + ");'>移除</a>"
                        );
                    }
                }];
</script>
@endsection
