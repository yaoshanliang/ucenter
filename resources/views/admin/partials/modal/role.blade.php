<!-- Modal -->
<div class="modal fade" id="choose_role_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:700px; margin-top:40px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title" id="myModalLabel">角色管理</h5>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover" id="role_index" class="display" cellspacing="0" width="100%" border='0px'>
                    <thead>
                        <tr>
                            <td style="width:15px"></td>
                            <td>名称</td>
                            <td>代号</td>
                            <td>描述</td>
                            <td>更新时间</td>
                        </tr>
                    </thead>
                    <tbody id="role_index_tbody">
                    </tbody>
                </table>
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
<script>
// 绑定模态框关闭后重绘事件
$("#choose_role_modal").on('hidden.bs.modal', function (table) {
    $('#' + datatable_id).DataTable().draw(false);
})
</script>
