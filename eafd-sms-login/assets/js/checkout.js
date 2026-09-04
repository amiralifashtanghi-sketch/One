jQuery(document).ready(function($) {

    if (!eafd_checkout_obj || !parseInt(eafd_checkout_obj.checkout_verify_enabled)) {
        return;
    }

    if (parseInt(eafd_checkout_obj.is_user_logged_in)) {
        return; // Logged in users do not require modal verification
    }

    var isPhoneVerified = false;

    $(document.body).on('checkout_place_order', function() {
        if (isPhoneVerified) {
            return true;
        }

        var phone = $('#billing_phone').val();
        if (!phone) {
            alert('لطفاً شماره تلفن همراه را در فرم تسویه حساب وارد نمایید.');
            return false;
        }

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
                if (res.success) {
                    $('#eafd-checkout-target-phone').text(res.data.phone);
                    $('#eafd-checkout-modal-overlay').fadeIn();
                } else {
                    alert(res.data.message);
                }
            },
            error: function() {
                alert('خطا در ارسال کد تایید.');
            }
        });

        return false; // Prevent form submit until OTP is verified
    });

    // OTP Input Navigation
    $('.eafd-checkout-otp-digit').on('keyup input', function(e) {
        var $this = $(this);
        if ($this.val().length >= 1) {
            $this.next('.eafd-checkout-otp-digit').focus();
        }
        if (e.keyCode === 8 && $this.val().length === 0) {
            $this.prev('.eafd-checkout-otp-digit').focus();
        }
    });

    // Verify OTP on Checkout Modal
    $('#eafd-btn-checkout-verify-otp').on('click', function(e) {
        e.preventDefault();
        var phone = $('#billing_phone').val();
        var code = '';
        $('.eafd-checkout-otp-digit').each(function() {
            code += $(this).val();
        });

        if (code.length < 4) {
            alert('لطفاً کد ۴ رقمی را به صورت کامل وارد کنید.');
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
                    alert(res.data.message);
                }
            },
            error: function() {
                $btn.prop('disabled', false).text('تایید کد و ثبت سفارش');
                alert('خطا در بررسی کد.');
            }
        });
    });

    // Close Modal
    $('#eafd-modal-close').on('click', function() {
        $('#eafd-checkout-modal-overlay').fadeOut();
    });
});
