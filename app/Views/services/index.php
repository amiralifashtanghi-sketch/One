<div class="page-header" style="text-align:center; margin-bottom:40px;">
    <h1>خدمات تخصصی وب پلتفرم EAFD</h1>
    <p>ارائه راهکارهای وب اختصاصی، ۱۰۰٪ کدنویسی شده بدون قالب‌های آماده و با کدهای کاملاً بهینه شده</p>
</div>

<div class="grid grid-cols-3">
    <?php foreach ($services as $srv): ?>
        <div class="card">
            <div style="font-size:2.5rem; margin-bottom:15px;"><?= $srv['icon'] ?? '🌐' ?></div>
            <h2 style="font-size:1.3rem; margin-bottom:10px;"><?= htmlspecialchars($srv['title']) ?></h2>
            <p style="font-size:0.95rem; margin-bottom:20px;"><?= htmlspecialchars($srv['summary']) ?></p>
            <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid var(--eafd-color-border); padding-top:15px;">
                <span style="color:var(--eafd-color-secondary); font-weight:bold;"><?= htmlspecialchars($srv['price_start']) ?></span>
                <a href="/services/<?= htmlspecialchars($srv['slug']) ?>" class="btn btn-secondary" style="padding:6px 14px; font-size:0.85rem;">مشاهده جزئیات ←</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
