<h2>گام ۴: ساخت جداول و درج داده‌های اولیه فارسی</h2>
<p>سیستم در حال اجرای مایگریشن‌ها و ورود اطلاعات نمونه (خدمات، نمونه پروژه‌ها، فروشگاه، ابزارها و برگه اصلی) می‌باشد.</p>

<?php if (isset($installerError) && $installerError): ?>
    <div class="alert alert-danger" style="margin-bottom:20px;">
        <strong>اجرای ساختار پایگاه داده با خطا مواجه شد:</strong><br>
        <?= htmlspecialchars($installerError) ?>
    </div>
<?php endif; ?>

<form method="POST" action="index.php?step=4">
    <button type="submit" name="run_migrations" value="1" class="btn btn-primary" style="padding:15px 30px; font-size:1.1rem;">
        شروع ساخت جداول و ورود اطلاعات نمونه ←
    </button>
</form>
