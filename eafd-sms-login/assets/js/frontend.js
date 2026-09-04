jQuery(document).ready(function($) {

    // Phone Check Handler
    $('#eafd-btn-check-phone').on('click', function(e) {
        e.preventDefault();
        var phone = $('#eafd_phone_input').val();

        if (!phone) {
            alert('لطفاً شماره همراه خود را وارد نمایید.');
            return;
        }

        var $btn = $(this);
        $btn.prop('disabled', true).text('در حال بررسی...');

        $.ajax({
            url: eafd_sms_obj.ajax_url,
            type: 'POST',
            data: {
                action: 'eafd_check_phone',
                phone: phone,
                nonce: eafd_sms_obj.nonce
            },
            success: function(res) {
                $btn.prop('disabled', false).text('ادامه');
                if (res.success) {
                    if (res.data.has_password) {
                        $('#eafd-step-phone').hide();
                        $('#eafd-step-password').show();
                    } else {
                        $('#eafd-step-phone').hide();
                        $('#eafd-target-phone').text(res.data.phone);
                        $('#eafd-step-otp').show();
                        startTimer(120);
                    }
                } else {
                    alert(res.data.message);
                }
            },
            error: function() {
                $btn.prop('disabled', false).text('ادامه');
                alert('خطا در ارتباط با سرور.');
            }
        });
    });

    // Password Login Handler
    $('#eafd-btn-login-password').on('click', function(e) {
        e.preventDefault();
        var phone = $('#eafd_phone_input').val();
        var password = $('#eafd_password_input').val();

        if (!password) {
            alert('لطفاً کلمه عبور را وارد کنید.');
            return;
        }

        var $btn = $(this);
        $btn.prop('disabled', true).text('در حال ورود...');

        $.ajax({
            url: eafd_sms_obj.ajax_url,
            type: 'POST',
            data: {
                action: 'eafd_login_password',
                phone: phone,
                password: password,
                nonce: eafd_sms_obj.nonce
            },
            success: function(res) {
                if (res.success) {
                    window.location.reload();
                } else {
                    $btn.prop('disabled', false).text('ورود با کلمه عبور');
                    alert(res.data.message);
                }
            },
            error: function() {
                $btn.prop('disabled', false).text('ورود با کلمه عبور');
                alert('خطا در ارتباط با سرور.');
            }
        });
    });

    // Switch to OTP Handler
    $('#eafd-btn-switch-to-otp').on('click', function(e) {
        e.preventDefault();
        var phone = $('#eafd_phone_input').val();

        var $btn = $(this);
        $btn.prop('disabled', true);

        $.ajax({
            url: eafd_sms_obj.ajax_url,
            type: 'POST',
            data: {
                action: 'eafd_send_otp',
                phone: phone,
                nonce: eafd_sms_obj.nonce
            },
            success: function(res) {
                $btn.prop('disabled', false);
                if (res.success) {
                    $('#eafd-step-password').hide();
                    $('#eafd-target-phone').text(phone);
                    $('#eafd-step-otp').show();
                    startTimer(120);
                } else {
                    alert(res.data.message);
                }
            }
        });
    });

    // OTP Input Navigation
    $('.eafd-otp-digit').on('keyup input', function(e) {
        var $this = $(this);
        if ($this.val().length >= 1) {
            $this.next('.eafd-otp-digit').focus();
        }
        if (e.keyCode === 8 && $this.val().length === 0) { // Backspace
            $this.prev('.eafd-otp-digit').focus();
        }
    });

    // Verify OTP Handler
    $('#eafd-btn-verify-otp').on('click', function(e) {
        e.preventDefault();
        var phone = $('#eafd_phone_input').val();
        var code = '';
        $('.eafd-otp-digit').each(function() {
            code += $(this).val();
        });

        if (code.length < 4) {
            alert('لطفاً کد ۴ رقمی را به صورت کامل وارد کنید.');
            return;
        }

        var $btn = $(this);
        $btn.prop('disabled', true).text('در حال بررسی...');

        $.ajax({
            url: eafd_sms_obj.ajax_url,
            type: 'POST',
            data: {
                action: 'eafd_verify_otp',
                phone: phone,
                code: code,
                nonce: eafd_sms_obj.nonce
            },
            success: function(res) {
                $btn.prop('disabled', false).text('تایید کد و ورود');
                if (res.success) {
                    if (res.data.is_new) {
                        $('#eafd_reg_token').val(res.data.token);
                        $('#eafd-step-otp').hide();
                        $('#eafd-step-name').show();
                    } else {
                        window.location.reload();
                    }
                } else {
                    alert(res.data.message);
                }
            },
            error: function() {
                $btn.prop('disabled', false).text('تایید کد و ورود');
                alert('خطا در بررسی کد.');
            }
        });
    });

    // Complete Registration Handler
    $('#eafd-btn-complete-reg').on('click', function(e) {
        e.preventDefault();
        var phone = $('#eafd_phone_input').val();
        var firstName = $('#eafd_first_name').val();
        var lastName = $('#eafd_last_name').val();

        if (!firstName || !lastName) {
            alert('لطفاً نام و نام خانوادگی خود را کامل وارد کنید.');
            return;
        }

        var $btn = $(this);
        $btn.prop('disabled', true).text('در حال ثبت‌نام...');

        var token = $('#eafd_reg_token').val();

        $.ajax({
            url: eafd_sms_obj.ajax_url,
            type: 'POST',
            data: {
                action: 'eafd_complete_registration',
                phone: phone,
                token: token,
                first_name: firstName,
                last_name: lastName,
                nonce: eafd_sms_obj.nonce
            },
            success: function(res) {
                if (res.success) {
                    window.location.reload();
                } else {
                    $btn.prop('disabled', false).text('تکمیل ثبت‌نام و ورود');
                    alert(res.data.message);
                }
            }
        });
    });

    // Timer Helper Function
    function startTimer(duration) {
        var timer = duration, minutes, seconds;
        $('#eafd-btn-resend-otp').hide();
        $('#eafd-timer').show();

        var interval = setInterval(function() {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            $('#eafd-timer').text(minutes + ":" + seconds);

            if (--timer < 0) {
                clearInterval(interval);
                $('#eafd-timer').hide();
                $('#eafd-btn-resend-otp').show();
            }
        }, 1000);
    }
});
