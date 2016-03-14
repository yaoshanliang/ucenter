@extends('admin.base')

@section('content')
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
    <div class="panel-heading">用户总库
        <div class="pull-right">
            <a href="/admin/user"><i class="fa fa-user"></i> 用 户 </a> /
            用户总库
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
                <table class="table table-striped table-bordered table-hover" id="app_apply" class="display" cellspacing="0" width="100%" border='0px'>
                    <thead>
                        <tr>
                            <td style="width:15px"><input class="checkbox" type="checkbox" name="id" id='checkAll'></td>
                            <td>用户名</td>
                            <td>邮箱</td>
                            <td>手机</td>
                            <td>接入/退出</td>
                            <td>申请时间</td>
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
@include('admin.partials.modal.handleAppApply')

<script>

var datatable_id = 'app_apply';
var columnDefs_targets = [0];
var order = [5, 'desc'];
var ajax_url = '/admin/user/accesslists';
var delete_url = '/admin/user/delete';
var columns = [{
                    "data": "id",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html("<input type='checkbox' id='" + sData + "' class='checkbox' name='ids' value='" + sData + "'>");
                    }
                },
                {"data": "user_id"},
                {"data": "user_id"},
                {"data": "user_id"},
                {
                    "data": "type",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        data = ('access' ==sData) ? '接入' : '退出';
                        $(nTd).html(data);
                    }
                },
                {"data": "created_at"},
                {
                    "data": "user_id",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        if (0 === oData.handler_id) {
                            if ('access' == oData.type) {
                                data = "<button type='button' onclick='return chooseRole(" + sData + ");' class='btn btn-outline btn-primary btn-xs'>同意</button>" + " ";
                                data += "<button type='button' onclick='return handleAppApply(" + "\"" + oData.type + "\"," + "\"disagree\"," + sData + ");' class='btn btn-outline btn-danger btn-xs'>拒绝</button>";
                            } else {
                                data = "<button type='button' onclick='return handleAppApply(" + "\"" + oData.type + "\"," + "\"agree\"," + sData + ");' class='btn btn-outline btn-primary btn-xs'>同意</button>" + " ";
                                data += "<button type='button' onclick='return handleAppApply(" + "\"" + oData.type + "\"," + "\"disagree\"," + sData + ");' class='btn btn-outline btn-danger btn-xs'>拒绝</button>";
                            }
                        } else {
                            data = "<span class='text-success'>已处理</span>";
                        }
                        $(nTd).html(data);
                    }
                }];
</script>
@endsection
