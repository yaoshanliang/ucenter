function sendCode() {
    var phone = $('input[name="phone"]').val();
    if (phone.length != 11) {
        showFailTip('手机号不合法');
        return false;
    }

    $.ajax({
        url: '/api/sms/sendCode',
        data: {'phone': phone, 'access_token': $('input[name="access_token"]').val()},
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        success: function(data) {
            if(data['code'] === 1) {
                showSuccessTip(data['message']);
                var InterValObj;
                var total_count = 60;//总倒计时秒数
                var current_count;
                current_count = total_count;
                $("#send_code").val(current_count + "(s)后重发");
                InterValObj = window.setInterval(setRemainTime, 1000);
                $("#send_code").addClass('disabled');
                $("#send_code").removeClass('btn-outline');
                //计时器
                function setRemainTime() {
                    if (current_count == 0) {
                        window.clearInterval(InterValObj);
                        $("#send_code").removeClass("disabled");
                        $("#send_code").addClass('btn-outline');
                        $("#send_code").val("发送验证码");
                    } else {
                        current_count--;
                        $("#send_code").val(current_count + "(s)后重发");
                    }
                }

            } else {
                showFailTip(data['message']);
                return false;
            }
        },
        error: function(data) {
            showFailTip(data['message']);
            return false;
        },
    });
}

function validateCode() {
    var phone = $('input[name="phone"]').val();
    if (phone.length != 11) {
        showFailTip('手机号不合法');
        return false;
    }
    var code = $('input[name="code"]').val();
    if (code == '') {
        showFailTip('验证码必填');
        return false;
    }
    $.ajax({
        url: '/api/sms/validateCode',
        data: {'phone': phone, 'code': code, 'access_token': $('input[name="access_token"]').val()},
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        success: function(data) {
            if(data['code'] === 1) {
                confirmEdit('phone');
            } else {
                showFailTip(data['message']);
                return false;
            }
        },
        error: function(data) {
            showFailTip(data['message']);
            return false;
        },
    });
}
