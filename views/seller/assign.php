<?php
ob_start();
?>

<h2 style="margin-top: 0; margin-bottom: 1.5rem;">تخصیص کارت بیو به خریدار جدید</h2>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem;">
    <!-- Scanner / Manual Input Card -->
    <div class="card">
        <h3 style="margin-top:0;">۱. دریافت کد یا اسکن کیو آر کد کارت</h3>

        <div id="reader" style="width: 100%; border-radius: 8px; overflow: hidden; margin-bottom: 1rem; background: #000; min-height: 200px; display: flex; align-items: center; justify-content: center; color: #fff;">
            در حال بارگذاری دوربین...
        </div>

        <form method="POST" action="/seller/assign" id="assignForm">
            <div class="form-group">
                <label>کد کارت یا آدرس اسکن شده (مثال: card_abc123):</label>
                <input type="text" id="card_code" name="card_code" placeholder="کد کارت یا لینک QR" required>
            </div>

            <div class="form-group">
                <label>شماره همراه خریدار (مشتری):</label>
                <input type="text" name="buyer_phone" placeholder="۰۹۱۲۱۲۳۴۵۶۷" required>
            </div>

            <div class="form-group">
                <label>تعیین رمز عبور اولیه برای خریدار:</label>
                <input type="text" name="buyer_password" placeholder="حداقل ۴ کاراکتر" value="123456" required>
                <div style="font-size: 0.8rem; color: var(--muted); margin-top: 0.25rem;">
                    خریدار با این شماره همراه و رمز عبور وارد پنل بیو خود خواهد شد.
                </div>
            </div>

            <button type="submit" class="btn" style="width: 100%;">ثبت و واگذاری مالکیت به خریدار &check;</button>
        </form>
    </div>

    <!-- Help Card -->
    <div class="card">
        <h3 style="margin-top: 0;">راهنمای فروشنده</h3>
        <ol style="line-height: 1.8; color: var(--muted); padding-right: 1.25rem;">
            <li>دوربین گوشی خود را در مقابل QR Code روی کارت ویزیت فیزیکی نگه دارید.</li>
            <li>کد کارت به صورت خودکار در کادر بالا درج می‌شود (یا می‌توانید به صورت دستی وارد کنید).</li>
            <li>شماره همراه خریدار را وارد کنید.</li>
            <li>یک رمز عبور اولیه برای مشتری ثبت نمایید.</li>
            <li>پس از دکمه ثبت، مالکیت کارت فوری به مشتری منتقل شده و پیامک خوش‌آمدگویی برای ایشان ارسال می‌گردد.</li>
        </ol>
    </div>
</div>

<!-- html5-qrcode library CDN for instant web camera QR scanning -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (typeof Html5QrcodeScanner !== 'undefined') {
            const html5QrcodeScanner = new Html5QrcodeScanner(
                "reader",
                { fps: 10, qrbox: { width: 220, height: 220 } },
                /* verbose= */ false
            );

            html5QrcodeScanner.render(onScanSuccess, onScanFailure);

            function onScanSuccess(decodedText, decodedResult) {
                console.log(`Scan result: ${decodedText}`);
                document.getElementById('card_code').value = decodedText;
                alert('کد کارت با موفقیت اسکن شد: ' + decodedText);
            }

            function onScanFailure(error) {
                // Ignore standard frame scan errors
            }
        } else {
            document.getElementById('reader').innerText = 'اسکنر آنلاین آماده نیست، کد را دستی وارد کنید.';
        }
    });
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
