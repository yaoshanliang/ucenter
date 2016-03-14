/**
 * 显示操作成功信息
 *
 * @param  string 提示信息, float 显示时间
 * @return null
 */
function showSuccessTip(tip, time){
    var tip = arguments[0] || '操作成功';
    var time = arguments[1] || 3;
    var background = '#5cb85c';
    var bordercolor = '#4cae4c';

    showTip(tip, time, background, bordercolor);
}

/**
 * 显示操作失败信息
 * @param string 提示信息, float 显示时间
 * @return null
 */
function showFailTip(tip, time){
    var tip = arguments[0] || '操作失败';
    var time = arguments[1] || 3;
    var background = '#c9302c';
    var bordercolor = '#ac2925';

    showTip(tip, time, background, bordercolor);
}

/**
 * 显示信息，供成功、失败调用
 * @param string 提示信息, float 显示时间, string 背景颜色, string 边框颜色
 * @return null
 */
function showTip(tip, time, background, bordercolor) {
    var windowWidth = document.documentElement.clientWidth;
    var height = 10;
    var width = 200;
    var tipsDiv = '<div class="tipsClass">' + tip + '</div>';

    $('body').append(tipsDiv);
    $('div.tipsClass').css({
        'z-index': 9999,
        'top': height + 'px',
        'width': width + 'px',
        'height': '30px',
        'left': (windowWidth / 2) - (width / 2) + 'px',
        'position': 'fixed',
        'padding': '3px 5px',
        'background': background,
        'border': '1px solid transparent',
        'border-color': bordercolor,
        'border-radius':'4px',
        'font-size': 14 + 'px',
        'margin': '0 auto',
        'text-align': 'center',
        'color': '#fff',
        'opacity': '0.8'
    }).show();
    setTimeout(function(){$('div.tipsClass').fadeOut(); $('div.tipsClass').remove()}, (time * 1000));
}

function submit_datatable(type, datatable_id, url, ids, tip_msg, tip_time) {
    switch(type) {
        case 'delete':
            $('#confirm_delete_modal').modal('hide');
            break;
        case 'remove':
            $('#confirm_remove_modal').modal('hide');
            break;
    }
    $.ajax({
        url: url,
        type: 'DELETE',
        data: {'ids': ids},
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        success: function(data) {
            if(data['code'] === 1) {
                if (type == 'delete' && url == '/admin/app/delete') {
                    window.location.reload();
                } else {
                    $('#' + datatable_id).DataTable().draw(false);//保持分页
                }
                showSuccessTip(data['message']);
            } else {
                showFailTip(data['message']);
            }
        },
        error: function() {
            showFailTip(tip_msg, tip_time);
        },
    });
}

function initComplete() {
    $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-red',
        increaseArea: '20%' // optional
    });
    $(".select2").select2();
    //行列高亮
    var lastIdx = null;
    $('#' + datatable_id + ' tbody')
    .on( 'mouseover', 'td', function () {
        var colIdx = table.cell(this).index().column;
        if ( colIdx !== lastIdx ) {
            $( table.cells().nodes() ).removeClass( 'highlight' );
            $( table.column( colIdx ).nodes() ).addClass( 'highlight' );
        }
    } )
    .on( 'mouseleave', function () {
        $( table.cells().nodes() ).removeClass( 'highlight' );
    })
    table.on( 'draw', function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-red',
            increaseArea: '20%' // optional
        });
        $('#checkAll').on('ifChecked', function(event){
            $('input').iCheck('check');
        });
        $('#checkAll').on('ifUnchecked', function(event){
            $('input').iCheck('uncheck');
        });
        $('#checkAll').iCheck('uncheck');
        $(".select2").select2();
    } );
    //全选全不选
    $('#checkAll').on('ifChecked', function(event){
        $('input').iCheck('check');
    });
    $('#checkAll').on('ifUnchecked', function(event){
        $('input').iCheck('uncheck');
    });
    $("#search").on( 'keyup', function () {
        table.search( this.value )
        .draw();
    });
}
function check_delete(id) {
    var ids = [];
    if(!id) {
        $('input[name="ids"]:checked').each(function(){ ids.push($(this).val()); });
    } else {
        ids.push(id);
    }
    delete_ids = ids;
    if(ids == '') {
        $('#no_selected_modal').modal('show');
    } else {
        $('#confirm_delete_modal').modal('show');
    }
}
function check_remove(id) {
    var ids = [];
    if (!id) {
        $('input[name="ids"]:checked').each(function(){ ids.push($(this).val()); });
    } else {
        if (typeof(id) == 'object') {
            ids = id;
        } else {
            ids.push(id);
        }
    }
    remove_ids = ids;
    if(ids == '') {
        $('#no_selected_modal').modal('show');
    } else {
        $('#confirm_remove_modal').modal('show');
    }
}

function change_app(url, app_id) {
    $.ajax({
        url: url,
        type: 'PUT',
        data: {'app_id': app_id},
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        success: function(data) {
            if(data['code'] === 1) {
                window.location.reload();
                showSuccessTip(data['message']);
            } else {
                showFailTip(data['message']);
            }
        },
        error: function() {
            showFailTip(data['message']);
        },
    });
}
function change_role(url, role_id) {
    $.ajax({
        url: url,
        type: 'PUT',
        data: {'role_id': role_id},
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        success: function(data) {
            if(data['code'] === 1) {
                window.location.href = data['data']['redirect'];
                showSuccessTip(data['message']);
            } else {
                showFailTip(data['message']);
            }
        },
        error: function() {
            showFailTip(data['message']);
        },
    });
}

// 选中角色
function chooseRole(user_id) {
    $.getJSON('/admin/user/role/' + user_id, function(data) {
        if (data.code === 1) {
            data = data.data;
            var html;
            for (var i = 0; i < data.length; i++) {
                html += '<tr>';
                if (data[i].checked) {
                    html += '<td><input class="checkbox" type="checkbox" name="id" checked="checked" value=' + data[i].id + '></input></td>';
                } else {
                    html += '<td><input class="checkbox" type="checkbox" name="id" value="' + data[i].id + '"></input></td>';
                }
                html += '<td>' + data[i].title + '</td>';
                html += '<td>' + data[i].name + '</td>';
                html += '<td>' + data[i].description + '</td>';
                html += '<td>' + data[i].updated_at + '</td>';
            }
            var nTr = $("#role_index_tbody").html(html);
        }
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            increaseArea: '20%' // optional
        });
        $('input').on('ifChecked', function(event){
            selectOrUnselectRole(user_id, $(this).val())
        });
        $('input').on('ifUnchecked', function(event){
            selectOrUnselectRole(user_id, $(this).val())
        });
    });
    $("#choose_role_modal").modal('show');
}

// 勾选或者取消勾选角色
function selectOrUnselectRole(user_id, role_id) {
    $.ajax({
        url: '/admin/user/role',
        type: 'PUT',
        data: {'user_id': user_id, 'role_id': role_id},
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        success: function(data) {
            if(data['code'] === 1) {
                showSuccessTip(data['message']);
            } else {
                showFailTip(data['message']);
                return false;
            }
        },
    });
}

// 勾选或者取消勾选权限
function selectOrUnselectPermission(role_id, permission_id) {
    $.ajax({
        url: '/admin/role/permission',
        type: 'PUT',
        data: {'role_id': role_id, 'permission_id': permission_id},
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        success: function(data) {
            if(data['code'] === 1) {
                showSuccessTip(data['message']);
            } else {
                showFailTip(data['message']);
                return false;
            }
        },
    });
}

// 申请接入、退出应用
function appApply(method, type, id) {
    $('input[name="method"]').val(method);
    $('input[name="type"]').val(type);
    $('input[name="app_id"]').val(id);

    if ('delete' == method) {
        $("#modal-title").html('取消申请');
        $('input[name="title"]').val('取消申请');
    } else if ('exit' == type) {
        $("#modal-title").html('取消接入');
        $('input[name="title"]').val('申请取消接入');
    }
    $('#app_apply').modal('show');
}

function apply() {
    method = $('input[name="method"]').val();
    type = $('input[name="type"]').val();
    id = $('input[name="app_id"]').val();
    title = $('input[name="title"]').val();
    description = $('textarea[name="description"]').val();
    $.ajax({
        url: '/home/app/access',
        type: method,
        data: {'type': type, 'app_id': id, 'title': title, 'description': description},
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        success: function(data) {
            if(data['code'] === 1) {
                showSuccessTip(data['message']);
                $('#app_apply').modal('hide');
                $('#app_all').DataTable().draw(false);//保持分页
            } else {
                showFailTip(data['message']);
                return false;
            }
        },
    });
}

// 处理用户申请
function handleAppApply(type, result, user_id) {
    $('input[name="type"]').val(type);
    $('input[name="result"]').val(result);
    $('input[name="user_id"]').val(user_id);

    if ('agree' == result) {
        $('input[name="title"]').val('同意退出');
    }
    $('#handle_app_apply').modal('show');
}

function handleApply() {
    user_id = $('input[name="user_id"]').val();
    type = $('input[name="type"]').val();
    result = $('input[name="result"]').val();
    reason = $('textarea[name="reason"]').val();
    $.ajax({
        url: '/admin/user/access',
        type: 'PUT',
        data: {'user_id': user_id, 'type': type, 'result': result, 'reason': reason},
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        success: function(data) {
            if(data['code'] === 1) {
                showSuccessTip(data['message']);
                $('#handle_app_apply').modal('hide');
                $('#app_apply').DataTable().draw(false);
            } else {
                showFailTip(data['message']);
                return false;
            }
        },
    });
}
