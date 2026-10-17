<?php
ob_start();
?>

<h2 style="margin-top: 0; margin-bottom: 1.5rem;">تنظیمات عمومی سامانه و SMS.ir</h2>

<div class="card" style="max-width: 650px;">
    <h3 style="margin-top: 0;">پیکربندی وب‌سرویس SMS.ir (پترن OTP)</h3>
    <form method="POST" action="/admin/settings/save">
        <div class="form-group">
            <label>عنوان اصلی سایت:</label>
            <input type="text" name="site_title" value="<?= htmlspecialchars($settings['site_title'] ?? 'سامانه کارت و بیو لینک اختصاصی') ?>" required>
        </div>
        <div class="form-group">
            <label>کلید API سامانه SMS.ir (API Key):</label>
            <input type="text" name="sms_api_key" value="<?= htmlspecialchars($settings['sms_api_key'] ?? '') ?>" placeholder="X-API-KEY را وارد کنید" required>
        </div>
        <div class="form-group">
            <label>شناسه پترن کد یکبار مصرف (Template ID):</label>
            <input type="text" name="sms_template_id" value="<?= htmlspecialchars($settings['sms_template_id'] ?? '') ?>" placeholder="مثال: 100000" required>
        </div>
        <div class="form-group">
            <label>نام متغیر کد در پترن (Parameter Name):</label>
            <input type="text" name="sms_param_name" value="<?= htmlspecialchars($settings['sms_param_name'] ?? 'CODE') ?>" required>
            <div style="font-size: 0.8rem; color: var(--muted); margin-top: 0.25rem;">
                نکته: در وب‌سرویس SMS.ir، متن پترن باید شامل این متغیر باشد (مثلاً: کد تایید شما {CODE} است).
            </div>
        </div>
        <button type="submit" class="btn btn-success" style="width: 100%;">ذخیره تنظیمات</button>
    </form>
</div>

<div class="card" style="max-width: 650px; margin-top: 1.5rem;">
    <h3 style="margin-top: 0;">تست زنده ارسال پیامک</h3>
    <form method="POST" action="/admin/settings/test-sms">
        <div class="form-group">
            <label>شماره همراه برای ارسال تست:</label>
            <input type="text" name="test_phone" placeholder="۰۹۱۲۱۲۳۴۵۶۷" required>
        </div>
        <button type="submit" class="btn" style="width: 100%;">ارسال پیامک تست (کد ۱۲۳۴)</button>
    </form>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
