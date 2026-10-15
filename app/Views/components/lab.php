<div class="lab-component">
    <div style="text-align:center; margin-bottom:40px;">
        <h2><?= htmlspecialchars($settings['title'] ?? 'آزمایشگاه و ابزارهای آنلاین EAFD') ?></h2>
        <p>ابزارهای آنلاین و آزمون‌های سنجش آمادگی وب‌سایت شما برای امتیاز ۱۰۰/۱۰۰</p>
    </div>

    <div class="grid grid-cols-2">
        <div class="card">
            <span style="font-size:2rem; display:block; margin-bottom:10px;">🎨</span>
            <h3 style="font-size:1.2rem; margin-bottom:10px;">محاسبه‌گر نسبت کنتراست WCAG 2.2</h3>
            <p style="font-size:0.9rem; margin-bottom:20px;">بررسی آنلاین کنتراست رنگ متن و پس‌زمینه بر اساس استانداردهای بین‌المللی دسترس‌پذیری.</p>
            <a href="/lab/tool/wcag-contrast-checker" class="btn btn-secondary">اجرای ابزار آنلاین ←</a>
        </div>
        <div class="card">
            <span style="font-size:2rem; display:block; margin-bottom:10px;">🧪</span>
            <h3 style="font-size:1.2rem; margin-bottom:10px;">آزمون سنجش آمادگی Lighthouse</h3>
            <p style="font-size:0.9rem; margin-bottom:20px;">پاسخ به ۵ سوال کوتاه جهت تحلیل میزان بهینه‌بودن معماری و سرعت وب‌سایت شما.</p>
            <a href="/lab/quiz/lighthouse-readiness-quiz" class="btn btn-secondary">شروع آزمون آنلاین ←</a>
        </div>
    </div>
</div>
