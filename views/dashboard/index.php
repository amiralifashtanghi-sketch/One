<?php
ob_start();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <h2 style="margin: 0;">مدیریت صفحه بیو لینک شما</h2>
    <a href="/<?= htmlspecialchars($bio['username']) ?>" target="_blank" class="btn" style="background: #2563eb;">مشاهده بیو آنلاین 🔗</a>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">
    <div class="card" style="margin:0;">
        <span style="color: var(--muted); font-size: 0.85rem;">آدرس اختصاصی بیو:</span>
        <div style="font-weight: bold; font-size: 1.1rem; color: var(--primary); margin-top: 0.5rem; word-break: break-all;">
            <?= htmlspecialchars($appUrl . '/' . $bio['username']) ?>
        </div>
    </div>
    <div class="card" style="margin:0;">
        <span style="color: var(--muted); font-size: 0.85rem;">تعداد کل بازدیدها:</span>
        <div style="font-weight: bold; font-size: 1.8rem; color: var(--text); margin-top: 0.25rem;">
            <?= number_format((int)($bio['views_count'] ?? 0)) ?> بار
        </div>
    </div>
    <div class="card" style="margin:0;">
        <span style="color: var(--muted); font-size: 0.85rem;">تعداد بلوک‌های فعال:</span>
        <div style="font-weight: bold; font-size: 1.8rem; color: var(--success); margin-top: 0.25rem;">
            <?= number_format((int)$blocksCount) ?> بلوک
        </div>
    </div>
    <div class="card" style="margin:0;">
        <span style="color: var(--muted); font-size: 0.85rem;">درخواست‌های تماس (Leads):</span>
        <div style="font-weight: bold; font-size: 1.8rem; color: var(--warning); margin-top: 0.25rem;">
            <?= number_format((int)$leadsCount) ?> ثبت شده
        </div>
    </div>
</div>

<div class="card">
    <h3 style="margin-top:0;">راهنمای سریع ساخت بیو جذاب</h3>
    <ul style="color: var(--muted); line-height: 1.8; padding-right: 1.25rem;">
        <li>از منوی سمت راست وارد بخش <strong>«مشخصات و پروفایل»</strong> شوید و نام، تصویر آواتار و توضیحات خود را ثبت کنید.</li>
        <li>از بخش <strong>«ظاهر و تم‌ها»</strong> یک پالت رنگی یا گرادینت دلخواه برای صفحه بیو انتخاب کنید.</li>
        <li>از بخش <strong>«مدیریت بلوک‌ها»</strong> می‌توانید بیش از ۱۸ نوع بلوک مختلف (شامل لینک‌ها، ویدیوی آپارات/یوتیوب، لیست محصولات با قیمت، گالری تصاویر، شمارش معکوس، شماره کارت پرداخت و...) اضافه کنید.</li>
        <li>در بخش <strong>«آمار و لیدها»</strong> می‌توانید آمار بازدید ۷ روز گذشته و اطلاعات تماس و نوبت‌های ثبت‌شده خریداران را مشاهده کنید.</li>
    </ul>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
