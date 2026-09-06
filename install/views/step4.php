<h2>گام ۴: ساخت جداول و درج داده‌های اولیه فارسی</h2>
<p>سیستم در حال اجرای مایگریشن‌ها و ورود اطلاعات نمونه (خدمات، نمونه پروژه‌ها، فروشگاه، ابزارها و برگه اصلی) می‌باشد.</p>

<?php if (isset($migrateSuccess) && $migrateSuccess): ?>
    <div class="alert alert-success">✓ ساخت جداول و ثبت دیتای اولیه فارسی با موفقیت انجام شد.</div>
    <a href="index.php?step=5" class="btn btn-primary">ادامه به تنظیم حساب مدیریت ←</a>
<?php else: ?>
    <form method="POST" action="index.php?step=4">
        <button type="submit" name="run_migrations" value="1" class="btn btn-primary">شروع ساخت جداول و ورود اطلاعات نمونه ←</button>
    </form>
<?php endif; ?>
