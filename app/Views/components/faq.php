<div class="faq-component">
    <div style="text-align:center; margin-bottom:40px;">
        <h2><?= htmlspecialchars($settings['title'] ?? 'سوالات متداول') ?></h2>
        <p>پاسخ شفاف به پرسش‌های رایج کارفرمایان در خصوص پلتفرم EAFD</p>
    </div>

    <div style="max-width:800px; margin:0 auto; display:flex; flex-direction:column; gap:15px;">
        <details class="card" style="cursor:pointer;">
            <summary style="font-size:1.1rem; font-weight:bold; color:var(--eafd-color-text);">آیا سیستم EAFD نیازمند سرور اختصاصی یا Node.js است؟</summary>
            <p style="margin-top:15px; margin-bottom:0; font-size:0.95rem;">خیر، پلتفرم EAFD به گونه‌ای طراحی شده که روی تمامی هاست‌های اشتراکی لینوکس با PHP 8.2+ و MySQL به بهترین شکل اجرا می‌شود.</p>
        </details>
        <details class="card" style="cursor:pointer;">
            <summary style="font-size:1.1rem; font-weight:bold; color:var(--eafd-color-text);">چگونه امتیاز ۱۰۰/۱۰۰ در هر ۵ معیار Lighthouse تضمین می‌شود؟</summary>
            <p style="margin-top:15px; margin-bottom:0; font-size:0.95rem;">با بارگذاری ۱۰۰٪ لوکال فونت‌ها و اسکریپت‌ها، حذف کتابخانه‌های سنگین خارجی، کدنویسی Progressive Enhancement و رعایت استاندارد WCAG 2.2 AA.</p>
        </details>
        <details class="card" style="cursor:pointer;">
            <summary style="font-size:1.1rem; font-weight:bold; color:var(--eafd-color-text);">سیستم لایسنس و تحویل امن فایل چگونه عمل می‌کند؟</summary>
            <p style="margin-top:15px; margin-bottom:0; font-size:0.95rem;">بلافاصله پس از پرداخت موفق، لایسنس یکتا صادر شده و دانلود فایل از پوشه غیرعمومی storage با توکن‌های امنیتی کوتاه‌عمر انجام می‌پذیرد.</p>
        </details>
    </div>
</div>
