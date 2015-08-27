<!-- jQuery -->
<script src="/admin-assets/js/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="/admin-assets/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="/admin-assets/js/metisMenu.min.js"></script>

<!-- DataTables JavaScript -->
<script src="/sb-admin/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="/sb-admin/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="/admin-assets/js/sb-admin-2.js"></script>

<!-- Page-Level Demo Scripts - Tables - Use for reference -->
<style>
td.highlight {
    background-color: whitesmoke !important;
}
</style>
<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
				"dom": '<"top"fi>rt<"bottom"lp><"clear">',
				"pagingType":   "full_numbers",
				"language": {
					"lengthMenu": "每页 _MENU_ 条记录",
					"zeroRecords": "没有找到记录",
					"info": "第 _PAGE_ 页 ( 总共 _PAGES_ 页 )",
					"infoEmpty": "无记录",
					"infoFiltered": "(从 _MAX_ 条记录过滤)",
					"search":"搜索：",
					"paginate":{
						"first":"首页",
						"previous":"上一页",
						"next":"下一页",
						"last":"尾页"
					}
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

