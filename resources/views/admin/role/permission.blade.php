@extends('admin.base')

@section('content')
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
    <div class="panel-heading">权限管理&emsp;&emsp;
        当前角色:<span class="text-success">{{ $role->title }}</span>
        <div class="pull-right">
            <a href="/admin/role"><i class="fa fa-eye"></i> 角 色 </a> /
            权限
        </div>
    </div>
    <div class="panel-body">

        <div class="dataTable_wrapper">
            <br />
            <div class="input-group custom-search-form">
            <a href='/admin/role/permissionselected/<?php echo $role->id; ?>' class="btn btn-primary">已拥有权限列表</a>
                &nbsp;
                <input type="text" id="search" class="form-control search" placeholder="搜索">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/role/app') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="selected_ids" id="selected_ids">
                <table class="table table-striped table-bordered table-hover" id="user_index" class="display" cellspacing="0" width="100%" border='0px'>
                    <thead>
                        <tr>
                            <td style="width:15px"></td>
                            <td>分组</td>
                            <td>代号</td>
                            <td>描述</td>
                            <td>创建时间</td>
                            <td>更新时间</td>
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
var columnDefs_targets = [0];
var order = [5, 'desc'];
var ajax_url = '/admin/role/permissionlists/<?php echo $role->id; ?>';
var remove_url = '/admin/role/remove';
var columns = [{
                    "data": "id",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html("<span class='row-details row-details-close' data_id='" + sData + "'></span>");
                    }
                },
                {"data": "title"},
                {"data": "name"},
                {"data": "description"},
                {"data": "created_at"},
                {"data": "updated_at"},
                ];

$('.table').on('click', ' tbody td .row-details',
    function() {
        var nTr = $(this).parents('tr')[0];
        if ($(this).hasClass("row-details-open")) {
            $(this).addClass("row-details-close").removeClass("row-details-open");
            $(this).parents('tr').next()[0].remove();
        } else {
            $(this).addClass("row-details-open").removeClass("row-details-close");
            openDetails(nTr, $(this).attr("data_id"));
        }
    }
);
function openDetails(nTr, id) {
    $.ajax({
        url: '/admin/role/permissiongroup/<?php echo $role->id; ?>/' + id,
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },

        beforeSend: function(xhr) {
           // oTable.fnOpen( nTr, '<span id="configure_chart_loading"><img src="${pageContext.request.contextPath }/image/select2-spinner.gif"/>详细信息加载中...</span>', 'details' );
        },

        success: function (data, textStatus) {
            if (data.code === 0) {
                data = data.data;
                var details = '<tr role="row"><td colspan=7><table width=100%>';
                if(data.length == 0) {
                    details += '<tr><td></td><td>无数据</td></tr>';
                } else {
                    for (var i = 0; i < data.length; i++) {
                        details += '<tr>';
                        details += '<td>&nbsp;&nbsp;</td>';
                        if (data[i].checked) {
                            details += '<td><input class="checkbox" type="checkbox" name="id" checked="checked" value=' + data[i].id + '></input></td>';
                        } else {
                            details += '<td><input class="checkbox" type="checkbox" name="id" value=' + data[i].id + '></input></td>';
                        }
                        details += '<td class="details" colspan=2>' + data[i].title + '</td>';
                        details += '<td class="details" colspan=2>' + data[i].name + '</td>';
                        details += '<td class="details" colspan=2>' + data[i].description + '</td>';
                        details += '</tr><tr><td>&nbsp;</td></tr>';
                    }
                }
                details+='</table></td></tr>';
                $(nTr).after(details);
            }

            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                increaseArea: '20%'
            });
            $('input').on('ifChecked', function(event){
                selectOrUnselectPermission('<?php echo $role->id; ?>', $(this).val())
            });
            $('input').on('ifUnchecked', function(event){
                selectOrUnselectPermission('<?php echo $role->id; ?>', $(this).val())
            });
        },

        error: function(){//请求出错处理
           // oTable.fnOpen( nTr,'加载数据超时~', 'details' );
       }
    });
}
</script>
@endsection
