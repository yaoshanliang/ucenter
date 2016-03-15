@extends('admin.base')

@section('content')
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
    <div class="panel-heading">处理申请
        <div class="pull-right">
            <a href="/admin/user"><i class="fa fa-user"></i> 用 户 </a> /
            处理申请
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
var columnDefs_targets = [0, 1, 2, 5];
var order = [4, 'desc'];
var ajax_url = '/admin/user/accesslists';
var delete_url = '/admin/user/delete';
var columns = [
                {"data": "username"},
                {"data": "email"},
                {"data": "phone"},
                {
                    "data": "type",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        data = ('access' ==sData) ? '<span class="text-success">申请接入</span>' : '<span class="text-danger">申请退出</span>';
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
                            result = ('agree' == oData.result) ? '同意' : '拒绝';
                            data = "已处理<span class='text-success'>(" + result + ")</span>";
                        }
                        $(nTd).html(data);
                    }
                }];
</script>
@endsection
