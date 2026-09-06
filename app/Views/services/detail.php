<div class="card" style="max-width:900px; margin:0 auto; padding:50px;">
    <div style="display:flex; align-items:center; gap:20px; margin-bottom:20px;">
        <span style="font-size:3rem;"><?= $service['icon'] ?? '🌐' ?></span>
        <div>
            <h1 style="margin:0; font-size:2rem;"><?= htmlspecialchars($service['title']) ?></h1>
            <span style="color:var(--eafd-color-secondary); font-weight:bold; font-size:1.1rem;"><?= htmlspecialchars($service['price_start']) ?></span>
        </div>
    </div>

    <p style="font-size:1.1rem; line-height:1.8; color:var(--eafd-color-text-muted); border-bottom:1px solid var(--eafd-color-border); padding-bottom:25px; margin-bottom:30px;">
        <?= htmlspecialchars($service['summary']) ?>
    </p>

    <div style="line-height:1.8; color:var(--eafd-color-text); margin-bottom:40px;">
        <?= nl2br(htmlspecialchars($service['content'])) ?>
    </div>

    <div style="text-align:center;">
        <a href="/contact" class="btn btn-primary" style="padding:15px 35px; font-size:1.1rem;">ثبت سفارش این خدمت ←</a>
    </div>
</div>
