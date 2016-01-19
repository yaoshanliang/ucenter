@extends('admin.base')

@section('content')
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
    <div class="panel-heading">权限管理</div>
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
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/role/app') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="selected_ids" id="selected_ids">
                <table class="table table-striped table-bordered table-hover" id="user_index" class="display" cellspacing="0" width="100%" border='0px'>
                    <thead>
                        <tr>
                            <td style="width:15px"><input class="checkbox" type="checkbox" name="id" id='checkAll'></td>
                            <td>分组</td>
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
var columnDefs_targets = [0, 6];
var order = [5, 'desc'];
var ajax_url = '/admin/role/<?php echo $role_id; ?>/permission_lists';
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
                {
                    "data": "id",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html("<a href=/admin/role/" + sData + "/permission>权限</a>" + " " + "<a href=/admin/role/" + sData + "/edit>编辑</a>" + " " +
                            "<a href='javascript:void(0);' onclick='return check_remove(" + sData + ");'>移除</a>");
                    }
                }];

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
        url: '/admin/permission/' + id + '/groupPermissions',
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },

        beforeSend: function(xhr) {
           // oTable.fnOpen( nTr, '<span id="configure_chart_loading"><img src="${pageContext.request.contextPath }/image/select2-spinner.gif"/>详细信息加载中...</span>', 'details' );
        },

        success: function (data, textStatus) {
            if(textStatus == "success"){  //转换格式 组合显示内容
                var details = '<tr role="row"><td colspan=7><table width=100%>';
                for (var i = 0; i < data.length; i++) {
                    details += '<tr>';
                    details += '<td>&nbsp;&nbsp;</td>';
                    details += '<td><input class="checkbox" type="checkbox" name="id" value=' + data[i].id + '></input></td>';
                    details += '<td class="details" colspan=2>' + data[i].title + '</td>';
                    details += '<td class="details" colspan=2>' + data[i].name + '</td>';
                    details += '<td class="details" colspan=2>' + data[i].description + '</td>';
                    details += '</tr>';
                }
                details+='</table></td></tr>';
                $(nTr).after(details);
            }

            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                increaseArea: '20%'
            });
            $('input').on('ifChecked', function(event){
                selectOrUnselect('select', '<?php echo $role_id; ?>', $(this).val())
            });
            $('input').on('ifUnChecked', function(event){
                selectOrUnselect('unselect', '<?php echo $role_id; ?>', $(this).val())
            });
        },

        error: function(){//请求出错处理
           // oTable.fnOpen( nTr,'加载数据超时~', 'details' );
       }
    });
}

function selectOrUnselect(type, role_id, permission_id) {
    if (type == 'select') {
        $.getJSON('/admin/role/' + role_id + '/selectPermission/' + permission_id, function(data, status, xhr) {

        });
    } else {
        $.getJSON('/admin/role/' + id + '/unselectPermission/' + permission_id, function(data, status, xhr) {

        });
    }
}
</script>
@endsection
