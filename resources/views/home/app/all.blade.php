@extends('home.base')

@section('content')
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
    <div class="panel-heading">应用总库
        <div class="pull-right">
            <a href="/admin/user"><i class="fa fa-user"></i> 应 用 </a> /
            应用总库
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
                <table class="table table-striped table-bordered table-hover" id="user_all" class="display" cellspacing="0" width="100%" border='0px'>
                    <thead>
                        <tr>
                            <td>名称</td>
                            <td>地址</td>
                            <td>创建时间</td>
                            <td>状态</td>
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
function choose_role(user_id) {
    $.getJSON('/admin/user/role/' + user_id, function(data) {
        if (data.code === 1) {
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


var datatable_id = 'user_all';
var columnDefs_targets = [3, 4];
var order = [2, 'desc'];
var ajax_url = '/home/app/alllists';
var delete_url = '/admin/user/delete';
var columns = [
                {"data": "title"},
                {
                    "data": "home_url",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html('<a target=_blank href=' + sData + '>' + sData + '</a>');
                    }
                },
                {"data": "created_at"},
                {
                    "data": "status",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        if (sData == 1) {
                            $(nTd).html('<span class="text-success">已接入</span>');
                        } else {
                            $(nTd).html('<span class="text-danger">未接入</span>');
                        }
                    }
                },
                {
                    "data": "id",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        if (oData.status === 1) {
                            data = "<button type='button' onclick='return applyExit(" + sData + ");' class='btn btn-outline btn-danger btn-xs'>取消接入</button>";
                        } else {
                            data = "<button type='button' onclick='return applyAccess(" + sData + ");' class='btn btn-outline btn-primary btn-xs'>申请接入</button>";
                        }
                        $(nTd).html(data);
                    }
                }];
</script>
@endsection
