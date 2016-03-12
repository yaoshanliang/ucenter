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
                <table class="table table-striped table-bordered table-hover" id="app_all" class="display" cellspacing="0" width="100%" border='0px'>
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
@include('home.partials.modal.appApply')

<script>
var datatable_id = 'app_all';
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
                        if (1 === sData) {
                            $(nTd).html('<span class="text-success">已接入</span>');
                        } else if ('access' == sData) {
                            $(nTd).html('<span class="text-warning">申请接入中</span>');
                        } else if ('exit' == sData) {
                            $(nTd).html('<span class="text-warning">申请取消接入中</span>');
                        } else {
                            $(nTd).html('<span class="text-danger">未接入</span>');
                        }
                    }
                },
                {
                    "data": "id",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        if (1 === oData.status) {
                            data = "<button type='button' onclick='return appApply(" + "\"post\"," + "\"exit\"," + sData + ");' class='btn btn-outline btn-danger btn-xs'>取消接入</button>";
                        } else if('access' == oData.status) {
                            data = "<button type='button' onclick='return appApply(" + "\"delete\"," + "\"access\"," + sData + ");' class='btn btn-outline btn-warning btn-xs'>取消申请</button>";
                        } else if('exit' == oData.status) {
                            data = "<button type='button' onclick='return appApply(" + "\"delete\"," + "\"exit\"," + sData + ");' class='btn btn-outline btn-warning btn-xs'>取消申请</button>";
                        } else {
                            data = "<button type='button' onclick='return appApply(" + "\"post\"," + "\"access\"," + sData + ");' class='btn btn-outline btn-primary btn-xs'>申请接入</button>";
                        }
                        $(nTd).html(data);
                    }
                }];
</script>
@endsection
