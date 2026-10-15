<div class="page-header" style="text-align:center; margin-bottom:40px;">
    <h1>فروشگاه محصولات دیجیتال EAFD</h1>
    <p>خرید قالب‌ها و افزونه‌های اختصاصی فوق‌سریع همراه با صدور خودکار لایسنس و تحویل امن</p>
</div>

<div class="grid grid-cols-2">
    <?php foreach ($products as $prod): ?>
        <div class="card">
            <span class="badge badge-success" style="margin-bottom:12px; display:inline-block;"><?= htmlspecialchars(strtoupper($prod['type'])) ?> نسخه <?= htmlspecialchars($prod['version']) ?></span>
            <h2 style="font-size:1.4rem; margin-bottom:10px;"><?= htmlspecialchars($prod['title']) ?></h2>
            <p style="font-size:0.95rem; margin-bottom:20px;"><?= htmlspecialchars($prod['summary']) ?></p>
            <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid var(--eafd-color-border); padding-top:15px;">
                <span style="color:var(--eafd-color-secondary); font-size:1.2rem; font-weight:bold;"><?= number_format($prod['price']) ?> تومان</span>
                <a href="/store/<?= htmlspecialchars($prod['slug']) ?>" class="btn btn-primary" style="padding:8px 18px; font-size:0.9rem;">مشاهده و خرید آنلاین ←</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
