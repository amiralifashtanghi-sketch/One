<div class="services-component">
    <div style="text-align:center; margin-bottom:40px;">
        <h2><?= htmlspecialchars($settings['title'] ?? 'خدمات تخصصی ما') ?></h2>
        <p>ارائه خدمات وب بر پایه معماری اختصاصی، سرعت بالا و رعایت کامل استانداردهای بین‌المللی</p>
    </div>

    <?php $servicesList = \App\Core\Database::fetchAll("SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order ASC"); ?>

    <div class="grid grid-cols-3">
        <?php foreach ($servicesList as $srv): ?>
            <div class="card">
                <div style="font-size:2.5rem; margin-bottom:15px;"><?= $srv['icon'] ?? '⚡' ?></div>
                <h3 style="font-size:1.3rem; margin-bottom:10px;"><?= htmlspecialchars($srv['title']) ?></h3>
                <p style="font-size:0.95rem; margin-bottom:20px;"><?= htmlspecialchars($srv['summary']) ?></p>
                <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid var(--eafd-color-border); padding-top:15px; font-size:0.9rem;">
                    <span style="color:var(--eafd-color-secondary); font-weight:bold;"><?= htmlspecialchars($srv['price_start']) ?></span>
                    <a href="/services/<?= htmlspecialchars($srv['slug']) ?>">جزئیات خدمت ←</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
