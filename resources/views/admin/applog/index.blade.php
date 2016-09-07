@extends('admin.base')

@section('content')
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
    <div class="panel-heading">用户日志</div>
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
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/userlog') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <table class="table table-striped table-bordered table-hover" id="user_index" class="display" cellspacing="0" width="100%" border='0px'>
                    <thead>
                        <tr>
                            <td>id</td>
                            <td>请求方式</td>
                            <td>请求地址</td>
                            <td>请求参数</td>
                            <td>返回代码</td>
                            <td>返回消息</td>
                            <td>返回数据</td>
                            <td>用户ID</td>
                            <td>用户IP</td>
                            <td>用户来源</td>
                            <td>用户agent</td>
                            <td>请求时间</td>
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
var columnDefs_targets = [];
var order = [0, 'desc'];
var ajax_url = '/admin/applog/lists';
var columns = [
                {"data": "id"},
                {"data": "request_method"},
                {"data": "request_url"},
                {
                    "data": "request_params",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html(sData.replace(/,/g, ', '));
                    }
                },
                {"data": "response_code"},
                {"data": "response_message"},
                {
                    "data": "response_data",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html(sData.replace(/#/g, '# '));
                    }
                },
                {"data": "user_id"},
                {"data": "user_ip"},
                {"data": "user_client"},
                {"data": "user_agent"},
                {"data": "request_time"},
                {
                    "data": "id",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html('<a href=# class="row-details row-details-closed" data_id=' + sData + '>详细</a>');
                    }
                }];

$('.table').on('click', ' tbody td .row-details',
    function() {
        var nTr = $(this).parents('tr')[0];
        if ($(this).hasClass("row-details-opened")) {
            $(this).addClass("row-details-closed").removeClass("row-details-opened");
            $(this).parents('tr').next()[0].remove();
            $(this).text('详细');
        } else {
            $(this).addClass("row-details-opened").removeClass("row-details-closed");
            openDetails(nTr, $(this).attr("data_id"));
            $(this).text('收起');
        }
    }
);

Date.prototype.format = function(format) {
    var date = {
        "M+": this.getMonth() + 1,
        "d+": this.getDate(),
        "h+": this.getHours(),
        "m+": this.getMinutes(),
        "s+": this.getSeconds(),
        "q+": Math.floor((this.getMonth() + 3) / 3),
        "S+": this.getMilliseconds()
    };
    if (/(y+)/i.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear() + '').substr(4 - RegExp.$1.length));
    }
    for (var k in date) {
        if (new RegExp("(" + k + ")").test(format)) {
            format = format.replace(RegExp.$1, RegExp.$1.length == 1
                ? date[k] : ("00" + date[k]).substr(("" + date[k]).length));
        }
    }
    return format;
}

function getTime(time) {
    return new Date(parseInt(time) * 1000).format('yyyy-MM-dd hh:mm:ss') + '.' + Math.round((time - parseInt(time)) * 10000);
}

function openDetails(nTr, id) {
    $.ajax({
        url: '/admin/applog/log/' + id,
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },

        beforeSend: function(xhr) {
           // oTable.fnOpen( nTr, '<span id="configure_chart_loading"><img src="${pageContext.request.contextPath }/image/select2-spinner.gif"/>详细信息加载中...</span>', 'details' );
        },

        success: function (data, textStatus) {
            if (data.code === 0) {
                var td00 = '<tr><td colspan=2><b>';
                var td01 = '</b></td>';
                var td10 = '<td colspan=11>';
                var td11 = '</td></tr>';

                data = data.data;
                var details = td00 + 'id' + td01 + td10 + data['id'] + td11;
                details += td00 + '请求数据' + td01 + td10 + data['request_params'].replace(/,/g, ', ') + td11;
                details += td00 + '返回数据' + td01 + td10 + data['response_data'].replace(/#/g, '# ') + td11;
                details += td00 + '用户agent' + td01 + td10 + data['user_agent'] + td11;
                details += td00 + '请求时间' + td01 + td10 + getTime(data['request_at']) + td11;
                details += td00 + '返回时间' + td01 + td10 + getTime(data['poped_at']) + td11;
                details += td00 + '发送日志' + td01 + td10 + getTime(data['pushed_at']) + td11;
                details += td00 + '写入日志' + td01 + td10 + getTime(data['created_at']) + td11;
                $(nTr).after(details);
             }
        },

        error: function(){//请求出错处理
           // oTable.fnOpen( nTr,'加载数据超时~', 'details' );
       }
    });
}
</script>
@endsection
