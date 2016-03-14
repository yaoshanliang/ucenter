<!-- Modal -->
<div class="modal fade" id="handle_app_apply" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:400px; margin-top:40px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title" id="modal-title">处理用户申请</h5>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <input type="hidden" name="user_id">
                    <input type="hidden" name="result">
                    <input type="hidden" name="type">
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="title" placeholder="拒绝" value="拒绝">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <textarea class="form-control" name="reason" rows="3" placeholder="理由"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary btn-block" onClick="return handleApply();">确认</button>
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
