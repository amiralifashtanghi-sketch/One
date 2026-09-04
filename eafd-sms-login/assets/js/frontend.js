jQuery(document).ready(function($) {

    var timerInterval = null;
    var failedAttempts = 0;

    function showInlineError(msg) {
        $('#eafd-msg-box').html('<div class="eafd-inline-error">' + msg + '</div>');
        failedAttempts++;
        if (failedAttempts >= 2 && eafd_sms_obj.support_phone) {
            if ($('#eafd-support-btn').length === 0) {
                $('#eafd-msg-box').append('<a href="tel:' + eafd_sms_obj.support_phone + '" id="eafd-support-btn" class="eafd-support-btn">📞 تماس با پشتیبانی (' + eafd_sms_obj.support_phone + ')</a>');
            }
        }
    }

    function clearInlineError() {
        $('#eafd-msg-box').empty();
    }

    // Phone Input Enter key listener
    $('#eafd_phone_input').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#eafd-btn-check-phone').click();
        }
    });

    // Password Input Enter key listener
    $('#eafd_password_input').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#eafd-btn-login-password').click();
        }
    });

    // Edit Phone Handler
    $('#eafd-btn-edit-phone').on('click', function(e) {
        e.preventDefault();
        clearInlineError();
        if (timerInterval) clearInterval(timerInterval);
        $('.eafd-step').hide();
        $('#eafd-step-phone').show();
        $('#eafd_phone_input').focus();
    });

    // Phone Check Handler
    $('#eafd-btn-check-phone').on('click', function(e) {
        e.preventDefault();
        clearInlineError();
        var phone = $('#eafd_phone_input').val();

        if (!phone) {
            showInlineError('لطفاً شماره همراه، نام کاربری یا ایمیل خود را وارد نمایید.');
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
                        $('#eafd_password_input').focus();
                    } else {
                        $('#eafd-step-phone').hide();
                        $('#eafd-target-phone').text(res.data.phone);
                        $('#eafd-step-otp').show();
                        $('.eafd-otp-digit[data-idx="1"]').focus();
                        startTimer(120);
                    }
                } else {
                    showInlineError(res.data.message);
                }
            },
            error: function() {
                $btn.prop('disabled', false).text('ادامه');
                showInlineError('خطا در ارتباط با سرور.');
            }
        });
    });

    // Password Login Handler
    $('#eafd-btn-login-password').on('click', function(e) {
        e.preventDefault();
        clearInlineError();
        var phone = $('#eafd_phone_input').val();
        var password = $('#eafd_password_input').val();

        if (!password) {
            showInlineError('لطفاً کلمه عبور را وارد کنید.');
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
                    window.location.href = res.data.redirect || window.location.href;
                } else {
                    $btn.prop('disabled', false).text('ورود با کلمه عبور');
                    showInlineError(res.data.message);
                }
            },
            error: function() {
                $btn.prop('disabled', false).text('ورود با کلمه عبور');
                showInlineError('خطا در ارتباط با سرور.');
            }
        });
    });

    // Switch to OTP Handler
    $('#eafd-btn-switch-to-otp').on('click', function(e) {
        e.preventDefault();
        clearInlineError();
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
                    $('.eafd-otp-digit[data-idx="1"]').focus();
                    startTimer(120);
                } else {
                    showInlineError(res.data.message);
                }
            }
        });
    });

    // Resend OTP Button Handler
    $('#eafd-btn-resend-otp').on('click', function(e) {
        e.preventDefault();
        clearInlineError();
        var phone = $('#eafd_phone_input').val();

        var $btn = $(this);
        $btn.prop('disabled', true).text('در حال ارسال...');

        $.ajax({
            url: eafd_sms_obj.ajax_url,
            type: 'POST',
            data: {
                action: 'eafd_send_otp',
                phone: phone,
                nonce: eafd_sms_obj.nonce
            },
            success: function(res) {
                $btn.prop('disabled', false).text('ارسال مجدد کد').hide();
                if (res.success) {
                    $('.eafd-otp-digit').val('');
                    $('.eafd-otp-digit[data-idx="1"]').focus();
                    startTimer(120);
                } else {
                    showInlineError(res.data.message);
                    $btn.show();
                }
            },
            error: function() {
                $btn.prop('disabled', false).text('ارسال مجدد کد').show();
                showInlineError('خطا در ارتباط با سرور.');
            }
        });
    });

    // OTP Digit Navigation & Auto-Submit
    $('.eafd-otp-digit').on('keyup input', function(e) {
        var $this = $(this);
        var val = $this.val().replace(/[^0-9]/g, '');
        $this.val(val);

        if (val.length >= 1) {
            var $next = $this.next('.eafd-otp-digit');
            if ($next.length) {
                $next.focus();
            }
        }

        if (e.keyCode === 8 && $this.val().length === 0) {
            $this.prev('.eafd-otp-digit').focus();
        }

        // Check if all 4 digits entered
        var code = '';
        $('.eafd-otp-digit').each(function() {
            code += $(this).val();
        });

        if (code.length === 4) {
            $('#eafd-btn-verify-otp').click();
        }
    });

    // Verify OTP Handler
    $('#eafd-btn-verify-otp').on('click', function(e) {
        e.preventDefault();
        clearInlineError();
        var phone = $('#eafd_phone_input').val();
        var code = '';
        $('.eafd-otp-digit').each(function() {
            code += $(this).val();
        });

        if (code.length < 4) {
            showInlineError('لطفاً کد ۴ رقمی را به صورت کامل وارد کنید.');
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
                        window.location.href = res.data.redirect || window.location.href;
                    }
                } else {
                    $('.eafd-otp-digit').val('');
                    $('.eafd-otp-digit[data-idx="1"]').focus();
                    showInlineError(res.data.message);
                }
            },
            error: function() {
                $btn.prop('disabled', false).text('تایید کد و ورود');
                showInlineError('خطا در بررسی کد.');
            }
        });
    });

    // Complete Registration Handler
    $('#eafd-btn-complete-reg').on('click', function(e) {
        e.preventDefault();
        clearInlineError();
        var phone = $('#eafd_phone_input').val();
        var firstName = $('#eafd_first_name').val();
        var lastName = $('#eafd_last_name').val();
        var token = $('#eafd_reg_token').val();

        if (!firstName || !lastName) {
            showInlineError('لطفاً نام و نام خانوادگی خود را کامل وارد کنید.');
            return;
        }

        var $btn = $(this);
        $btn.prop('disabled', true).text('در حال ثبت‌نام...');

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
                    showInlineError(res.data.message);
                }
            }
        });
    });

    // Timer Helper Function
    function startTimer(duration) {
        if (timerInterval) clearInterval(timerInterval);
        var timer = duration, minutes, seconds;
        $('#eafd-btn-resend-otp').hide();
        $('#eafd-timer').show();

        timerInterval = setInterval(function() {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            $('#eafd-timer').text(minutes + ":" + seconds);

            if (--timer < 0) {
                clearInterval(timerInterval);
                $('#eafd-timer').hide();
                $('#eafd-btn-resend-otp').show();
            }
        }, 1000);
    }
});
