<?php
ob_start();
?>

<h2 style="margin-top: 0; margin-bottom: 1.5rem;">📖 راهنمای جامع استفاده از سامانه بیو لینک و کارت ویزیت آنلاین</h2>

<div class="card" style="line-height: 1.8;">
    <h3 style="margin-top: 0; color: var(--primary);">۱. آدرس‌های اصلی سیستم (System URLs)</h3>
    <ul>
        <li><strong>صفحه نصب‌کننده آنلاین:</strong> <code>/install</code> (فقط در بار اول یا زمانی که فایل <code>config/installed.lock</code> نباشد اجرا می‌شود)</li>
        <li><strong>صفحه ورود همگانی:</strong> <code>/login</code> (برای ورود مدیر کل، فروشندگان و مالکان بیو با شماره همراه و رمز عبور یا کد OTP پیامکی)</li>
        <li><strong>بازیابی رمز عبور پیامکی:</strong> <code>/forgot-password</code></li>
        <li><strong>پنل مدیریت کل:</strong> <code>/admin</code></li>
        <li><strong>پنل فروشنده:</strong> <code>/seller</code> (دسترسی اختصاصی جهت تخصیص کارت به خریداران در بازار)</li>
        <li><strong>پنل مالک بیو (خریدار):</strong> <code>/dashboard</code> (مدیریت مشخصات بیو، انتخاب تم، افزودن بلوک‌های ۱۸ گانه)</li>
        <li><strong>آدرس بیو لینک خریدار:</strong> <code>/username</code> (مثال: <code>domain.com/ali</code>)</li>
        <li><strong>آدرس کارت/کیو آر کد:</strong> <code>/card_code</code> (مثال: <code>domain.com/card_12345</code> - در صورت تخصیص به خریدار، بلافاصله به بیو خریدار ریدایرکت می‌شود)</li>
    </ul>
</div>

<div class="card" style="line-height: 1.8;">
    <h3 style="margin-top: 0; color: var(--success);">۲. فرآیند فروش و واگذاری کارت آنلاین به مشتری</h3>
    <ol>
        <li>مدیر کل از بخش <strong>«مدیریت کارت‌ها»</strong> اقدام به ساخت کارت/کیو آر کد بدون مالک می‌کند (مثلاً ساخت ۵۰ کارت جدید).</li>
        <li>مدیر کل QR کدها یا کارت‌های فیزیکی را چاپ کرده و تحویل فروشنده‌ها می‌دهد.</li>
        <li>فروشنده در بازار با گوشی خود وارد <code>/seller/assign</code> می‌شود.</li>
        <li>فروشنده QR کد روی کارت فیزیکی را با دوربین اسکن کرده یا کد کارت را وارد می‌کند.</li>
        <li>فروشنده شماره همراه خریدار و یک رمز عبور اولیه ثبت می‌کند.</li>
        <li>سیستم کارت را به خریدار واگذار کرده و پیامک حاوی اطلاعات ورود برای خریدار ارسال می‌گردد.</li>
        <li>از این لحظه به بعد هرکس QR کد روی کارت فیزیکی را اسکن کند، مستقیماً به صفحه بیو خریدار (<code>/username</code>) منتقل می‌شود.</li>
    </ol>
</div>

<div class="card" style="line-height: 1.8;">
    <h3 style="margin-top: 0; color: var(--warning);">۳. تنظیمات وب‌سرویس پیامک SMS.ir</h3>
    <ul>
        <li>از بخش <strong>«تنظیمات سامانه و پیامک»</strong>، کلید <code>X-API-KEY</code> دریافت شده از SMS.ir را وارد کنید.</li>
        <li>شناسه پترن (Template ID) ثبت شده در SMS.ir را درج کنید (پترن Fast Send).</li>
        <li>متغیر کد پیامک (مثلاً <code>CODE</code>) را مطابق با نام متغیر موجود در متن پترن خود تنظیم نمایید.</li>
    </ul>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
