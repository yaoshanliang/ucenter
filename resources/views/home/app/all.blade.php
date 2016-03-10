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
                            data = "<button type='button' onclick='return appApply(" + "\"exit\"," + sData + ");' class='btn btn-outline btn-danger btn-xs'>取消接入</button>";
                        } else {
                            data = "<button type='button' onclick='return appApply(" + "\"access\"," + sData + ");' class='btn btn-outline btn-primary btn-xs'>申请接入</button>";
                        }
                        $(nTd).html(data);
                    }
                }];

function appApply(type, id) {
    $('input[name="type"]').val(type);
    $('input[name="app_id"]').val(id);

    if (type == 'exit') {
        $("#modal-title").html('取消接入');
        $('input[name="title"]').val('申请取消接入');
    }
    $('#app_apply').modal('show');
}

</script>
<!-- Modal -->
<div class="modal fade" id="app_apply" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:400px; margin-top:40px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title" id="modal-title">申请接入</h5>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <input type="hidden" name="app_id">
                    <input type="hidden" name="type">
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="title" placeholder="申请接入" value="申请接入">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <textarea class="form-control" name="description" rows="3" placeholder="理由"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary btn-block" onClick="return apply();">确认</button>
                        </div>
                    </div>
                </div>
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
@endsection
