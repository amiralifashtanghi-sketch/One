jQuery(document).ready(function($) {

    if (!eafd_checkout_obj || !parseInt(eafd_checkout_obj.checkout_verify_enabled)) {
        return;
    }

    if (parseInt(eafd_checkout_obj.is_user_logged_in)) {
        return; // Logged in users do not require modal verification
    }

    var isPhoneVerified = false;
    var timerInterval = null;
    var failedAttempts = 0;

    function showInlineError(msg) {
        $('#eafd-checkout-msg-box').html('<div class="eafd-inline-error">' + msg + '</div>');
        failedAttempts++;
        if (failedAttempts >= 2 && eafd_checkout_obj.support_phone) {
            if ($('#eafd-checkout-support-btn').length === 0) {
                $('#eafd-checkout-msg-box').append('<a href="tel:' + eafd_checkout_obj.support_phone + '" id="eafd-checkout-support-btn" class="eafd-support-btn">📞 تماس با پشتیبانی (' + eafd_checkout_obj.support_phone + ')</a>');
            }
        }
    }

    function clearInlineError() {
        $('#eafd-checkout-msg-box').empty();
    }

    // Intercept checkout submit button click
    $(document.body).on('click', '#place_order, form.checkout button[type="submit"]', function(e) {
        if (isPhoneVerified) {
            return true;
        }

        var phone = $('#billing_phone').val();
        if (!phone) {
            alert('لطفاً شماره تلفن همراه را در فرم تسویه حساب وارد نمایید.');
            e.preventDefault();
            e.stopPropagation();
            return false;
        }

        e.preventDefault();
        e.stopPropagation();

        var $btn = $(this);
        var originalText = $btn.text() || $btn.val();
        $btn.prop('disabled', true).text('⏳ در حال ارسال کد تایید پیامکی...');

        // Trigger SMS OTP to checkout phone
        $.ajax({
            url: eafd_checkout_obj.ajax_url,
            type: 'POST',
            data: {
                action: 'eafd_checkout_send_otp',
                phone: phone,
                nonce: eafd_checkout_obj.nonce
            },
            success: function(res) {
                $btn.prop('disabled', false).text(originalText);
                if (res.success) {
                    clearInlineError();
                    $('#eafd-checkout-target-phone').text(res.data.phone);
                    $('#eafd-checkout-modal-overlay').css('display', 'flex').hide().fadeIn();
                    $('.eafd-checkout-otp-digit[data-idx="1"]').focus();
                    startTimer(120);
                } else {
                    alert(res.data.message);
                }
            },
            error: function() {
                $btn.prop('disabled', false).text(originalText);
                alert('خطا در ارتباط با سرور هنگام ارسال کد تایید.');
            }
        });

        return false;
    });

    // OTP Digit Navigation & Auto Verify
    $('.eafd-checkout-otp-digit').on('keyup input', function(e) {
        var $this = $(this);
        var val = $this.val().replace(/[^0-9]/g, '');
        $this.val(val);

        if (val.length >= 1) {
            var $next = $this.next('.eafd-checkout-otp-digit');
            if ($next.length) {
                $next.focus();
            }
        }

        if (e.keyCode === 8 && $this.val().length === 0) {
            $this.prev('.eafd-checkout-otp-digit').focus();
        }

        var code = '';
        $('.eafd-checkout-otp-digit').each(function() {
            code += $(this).val();
        });

        if (code.length === 4) {
            $('#eafd-btn-checkout-verify-otp').click();
        }
    });

    // Resend OTP on Checkout Modal
    $('#eafd-btn-checkout-resend-otp').on('click', function(e) {
        e.preventDefault();
        clearInlineError();
        var phone = $('#billing_phone').val();

        var $btn = $(this);
        $btn.prop('disabled', true).text('در حال ارسال...');

        $.ajax({
            url: eafd_checkout_obj.ajax_url,
            type: 'POST',
            data: {
                action: 'eafd_checkout_send_otp',
                phone: phone,
                nonce: eafd_checkout_obj.nonce
            },
            success: function(res) {
                $btn.prop('disabled', false).text('ارسال مجدد کد').hide();
                if (res.success) {
                    $('.eafd-checkout-otp-digit').val('');
                    $('.eafd-checkout-otp-digit[data-idx="1"]').focus();
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

    // Verify OTP on Checkout Modal
    $('#eafd-btn-checkout-verify-otp').on('click', function(e) {
        e.preventDefault();
        clearInlineError();
        var phone = $('#billing_phone').val();
        var code = '';
        $('.eafd-checkout-otp-digit').each(function() {
            code += $(this).val();
        });

        if (code.length < 4) {
            showInlineError('لطفاً کد ۴ رقمی را به صورت کامل وارد کنید.');
            return;
        }

        var $btn = $(this);
        $btn.prop('disabled', true).text('در حال تایید...');

        $.ajax({
            url: eafd_checkout_obj.ajax_url,
            type: 'POST',
            data: {
                action: 'eafd_checkout_verify_otp',
                phone: phone,
                code: code,
                nonce: eafd_checkout_obj.nonce
            },
            success: function(res) {
                $btn.prop('disabled', false).text('تایید کد و ثبت سفارش');
                if (res.success) {
                    isPhoneVerified = true;
                    $('#eafd-checkout-modal-overlay').fadeOut();
                    // Re-submit checkout form
                    $('form.checkout').submit();
                } else {
                    $('.eafd-checkout-otp-digit').val('');
                    $('.eafd-checkout-otp-digit[data-idx="1"]').focus();
                    showInlineError(res.data.message);
                }
            },
            error: function() {
                $btn.prop('disabled', false).text('تایید کد و ثبت سفارش');
                showInlineError('خطا در بررسی کد.');
            }
        });
    });

    // Close Modal
    $('#eafd-modal-close').on('click', function() {
        $('#eafd-checkout-modal-overlay').fadeOut();
    });

    // Timer Helper Function
    function startTimer(duration) {
        if (timerInterval) clearInterval(timerInterval);
        var timer = duration, minutes, seconds;
        $('#eafd-btn-checkout-resend-otp').hide();
        $('#eafd-checkout-timer').show();

        timerInterval = setInterval(function() {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            $('#eafd-checkout-timer').text(minutes + ":" + seconds);

            if (--timer < 0) {
                clearInterval(timerInterval);
                $('#eafd-checkout-timer').hide();
                $('#eafd-btn-checkout-resend-otp').show();
            }
        }, 1000);
    }
});
