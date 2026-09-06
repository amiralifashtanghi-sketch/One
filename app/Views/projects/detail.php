<div class="card" style="max-width:900px; margin:0 auto; padding:50px;">
    <span class="badge badge-success" style="margin-bottom:15px; display:inline-block;"><?= htmlspecialchars($project['client_name']) ?></span>
    <h1 style="font-size:2.2rem; margin-bottom:15px;"><?= htmlspecialchars($project['title']) ?></h1>
    <p style="font-size:0.9rem; color:var(--eafd-color-secondary); margin-bottom:30px;">تکنولوژی‌های استفاده‌شده: <?= htmlspecialchars($project['technologies']) ?></p>

    <div class="grid grid-cols-3" style="gap:20px; margin-bottom:40px;">
        <div style="background:#0d131f; padding:20px; border-radius:8px;">
            <h3 style="color:#fca5a5; font-size:1.1rem;">چالش (Challenge)</h3>
            <p style="font-size:0.9rem; margin:0;"><?= htmlspecialchars($project['challenge'] ?? 'عدم سازگاری و کندی سیستم قبلی') ?></p>
        </div>
        <div style="background:#0d131f; padding:20px; border-radius:8px;">
            <h3 style="color:var(--eafd-color-secondary); font-size:1.1rem;">راهکار (Solution)</h3>
            <p style="font-size:0.9rem; margin:0;"><?= htmlspecialchars($project['solution'] ?? 'بازنویسی با معماری MVC اختصاصی EAFD') ?></p>
        </div>
        <div style="background:#0d131f; padding:20px; border-radius:8px;">
            <h3 style="color:#86efac; font-size:1.1rem;">نتیجه (Result)</h3>
            <p style="font-size:0.9rem; margin:0;"><?= htmlspecialchars($project['results'] ?? 'کاهش لود به زیر ۰.۵ ثانیه و امتیاز ۱۰۰ لایت‌هاوس') ?></p>
        </div>
    </div>

    <p style="font-size:1.1rem; line-height:1.8; color:var(--eafd-color-text-muted);">
        <?= htmlspecialchars($project['summary']) ?>
    </p>
</div>
