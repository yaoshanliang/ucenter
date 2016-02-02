@extends('home.base')

@section('content')
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
    <div class="panel-heading">接入应用
        <div class="pull-right">
            <i class="fa fa-th"></i>接入应用
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
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/home/app') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <table class="table table-striped table-bordered table-hover" id="app_index" class="display" cellspacing="0" width="100%" border='0px'>
                    <thead>
                        <tr>
                            <td style="width:15px"><input class="checkbox" type="checkbox" name="id" id='checkAll'></td>
                            <td>名称</td>
                            <td>地址</td>
                            <td>角色</td>
                            <td>接入时间</td>
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
var datatable_id = 'app_index';
var columnDefs_targets = [0, 3, 4, 5];
var order = [0, 'desc'];
var ajax_url = '/home/app/lists';
var remove_url = '/home/app/remove';
var columns = [{
                    "data": "id",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html("<input type='checkbox' id='" + sData + "' class='checkbox' name='ids' value='" + sData + "'>");
                    }
                },
                {"data": "title"},
                {
                    "data": "home_url",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html("<a href='" + sData + "' target='_blank'>" + sData + "</a>");
                    }
                },
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
                {
                    "data": "roles",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html(sData[0]['created_at']);
                    }
                },
                {
                    "data": "roles",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        var ids = [];
                        for (var i = 0; i < sData.length; i++) {
                            ids.push(sData[i]['id']);
                        }
                        $(nTd).html("<a href='javascript:void(0);' onclick='return check_remove([" + ids + "]);'>取消接入</a>");
                    }
                }];
</script>
@endsection
