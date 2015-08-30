<!-- jQuery -->
<script src="/admin-assets/js/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="/admin-assets/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="/admin-assets/js/metisMenu.min.js"></script>

<!-- DataTables JavaScript -->
<script src="/admin-assets/js/jquery.dataTables.js"></script>
<script src="/admin-assets/js/dataTables.bootstrap.min.js"></script>
<!-- <script src="/sb-admin/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>-->
<!-- <script src="/sb-admin/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>-->

<!-- Custom Theme JavaScript -->
<script src="/admin-assets/js/sb-admin-2.js"></script>

<!-- icheck JavaScript -->
<script src="/plugin/icheck/icheck.min.js"></script>

<!-- Page-Level Demo Scripts - Tables - Use for reference -->
<style>
td.highlight {
    background-color: whitesmoke !important;
}
.table-bordered thead tr td {
	border-bottom-width: 0px;
	// text-align: center;
}
div.dataTables_length {
	padding-top: 1px;
	color: #777;
}
</style>
<script>
    $(document).ready(function() {
		$('input').iCheck({
    checkboxClass: 'icheckbox_square-blue',
    radioClass: 'iradio_square-red',
    increaseArea: '20%' // optional
  });
        $('#dataTables-example').DataTable({
				columnDefs:[{
                 orderable:false,//禁用排序
                 targets:[0,4]   //指定的列
             }],
				// "dom": '<"top">rt<"bottom">lip<"clear">',
				// "dom": '<"top"i>rt<"bottom"flp><"clear">',
				"dom":
				   "<'row'<'col-sm-6'><'col-sm-6'f>r>"+
				   "t"+
				   "<'row'<'col-sm-6 pull-left'l><'col-sm-6 pull-right'p>>",
				"pagingType": "full_numbers",
				"lengthMenu": [[8, 25, 50, 100], [8, 25, 50, 100]],
				"language": {
					"processing" : "处理中...",
					"lengthMenu": "每页 _MENU_ 条， 共 _PAGES_ 页， 共 _TOTAL_ 条",
					"zeroRecords": "没有找到记录",
					"info": "共 _PAGES_ 页, _TOTAL_ 条",
					"infoEmpty": "无记录",
					"infoFiltered": "(从 _MAX_ 条记录过滤)",
					"search":"搜索：",
					"loadingRecords": "载入中...",
					"paginate":{
						"first":"首页",
						"previous":"上一页",
						"next":"下一页",
						"last":"尾页"
					}
				},
				"processing": true,
				"serverSide": true,
				"ajax": {
					"url": "scripts/server_processing.php",
					"type": 'POST',
					// "data":
				},
                responsive: true
        });
		var lastIdx = null;
    var table = $('#dataTables-example').DataTable();
    $('#dataTables-example tbody')
        .on( 'mouseover', 'td', function () {
            var colIdx = table.cell(this).index().column;
            if ( colIdx !== lastIdx ) {
                $( table.cells().nodes() ).removeClass( 'highlight' );
                $( table.column( colIdx ).nodes() ).addClass( 'highlight' );
            }
        } )
        .on( 'mouseleave', function () {
            $( table.cells().nodes() ).removeClass( 'highlight' );
        } );
    });
</script>

