@extends('home.base')

@section('content')
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
    <div class="panel-heading">我接入的
        <div class="pull-right">
            <i class="fa fa-th"></i>接入应用
        </div>
    </div>
    <div class="panel-body">
        <div class="dataTable_wrapper">
            <br />
            <div class="input-group custom-search-form">
                <a href="{{ URL('home/app/create') }}" class="btn btn-primary">创建应用</a>
                &nbsp;
                <a href="{{ URL('home/app/access') }}" class="btn btn-primary">我接入的</a>
                &nbsp;
                <a href="{{ URL('home/app/all') }}" class="btn btn-primary">应用总库</a>
                &nbsp;
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

@include('home.partials.modal.appApply')

<script>
var datatable_id = 'app_index';
var columnDefs_targets = [2, 3, 4];
var order = [0, 'desc'];
var ajax_url = '/home/app/accesslists';
var remove_url = '/home/app/remove';
var columns = [
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
                    "data": "id",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html("<button type='button' onclick='return appApply(" + "\"post\"," + "\"exit\"," + sData + ");' class='btn btn-outline btn-danger btn-xs'>取消接入</button>");
                    }
                }];
</script>
@endsection
