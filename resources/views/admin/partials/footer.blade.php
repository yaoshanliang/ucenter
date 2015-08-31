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

<script src="/admin-assets/js/colResizable-1.5.min.js"></script>

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
.JColResizer {
	 // display:none;
	 // margin:0,4px,0,4px !important;
}
</style>
<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
				// "aoColumnDefs": [ { "bSortable": false, "aTargets": [ 1,0,2 ] }],
				// columnDefs:[{
				 // orderable:false,//禁用排序
				 // targets:[0,1]   //指定的列
			 // }],
			 "order": [0,null],
			"columnDefs": [{
                       orderable: false, targets: 0 },{
                       orderable: false, targets: 6 }
               ],//第一列与第二列禁止排序
				// "columns": [
				// { "orderable": false },
				// { "orderable": false },
				// null,null],
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
				// "processing": true,
				"serverSide": true,
				"ajax": {
					"url": "/admin/user/lists",
					"type": 'POST',
					"data":{"name":123},
					dataType: 'json',
					headers: {
						'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
					},
					// success: function(data){
						// console.log(data);
					// },
					// error: function(xhr, type){
						// console.log('error');
					// }
				},
/*"columns": [
            { "data": "id" },
            { "data": "username" },
            { "data": "username" },
            { "data": "email" },
            { "data": "phone" },
		],*/
				"aoColumns": [
		{
			"mDataProp": "id",
			"fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
				// $(nTd).html('<div class="icheckbox_square-blue" style="position: relative;">'+
				// '<input class="checkbox" type="checkbox" name="user_id" id="checkAll"' +
				// 'style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);">'+
					// '<ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>'+
				// '</input>'+
				// '<div>');
				$(nTd).html("<input type='checkbox' class='checkbox' name='checkList' value='" + sData + "'>");

			}
		},
        {"mDataProp": "username"},
        {"mDataProp": "email"},
		{"mDataProp": "phone"},
		{"mDataProp": "created_at"},
		{"mDataProp": "updated_at"},
        {
            "mDataProp": "id",
            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                $(nTd).html("<a href='/admin/user/edit/'" + sData + ">编辑</a>");
				// "onclick='_editFun(\" + oData.id + "\",\"" + oData.name + "\",\"" + oData.job + "\",\"" + oData.note + "\")'>编辑</a>a>&nbsp;&nbsp;")
					// .append("<a href='javascript:void(0);' onclick='_deleteFun(" + sData + ")'>删除</a>a>");
            }
        },
	],
    // "sDom": "<'row-fluid'<'span6 myBtnBox'><'span6'f>r>t<'row-fluid'<'span6'i><'span6 'p>>",
    // "sPaginationType": "bootstrap",
                responsive: true,
		"initComplete": function () {
			// alert(22);
			// $("table").colResizable();
			// alert(33);
			// $("table").removeClass("JColResizer");
	},
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
		$('input').iCheck({
			checkboxClass: 'icheckbox_square-blue',
			radioClass: 'iradio_square-red',
			increaseArea: '20%' // optional
		});
		$('input').on('ifChecked', function(event){
			$('input').iCheck('check');
		});
		$('input').on('ifUnchecked', function(event){
			$('input').iCheck('uncheck');
		});
    });
</script>

