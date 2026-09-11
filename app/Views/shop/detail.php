<div class="card" style="max-width:850px; margin:0 auto; padding:40px;">
    <span class="badge badge-success" style="margin-bottom:15px; display:inline-block;"><?= htmlspecialchars(strtoupper($product['type'])) ?> - لایسنس مادام‌العمر</span>
    <h1 style="font-size:2.2rem; margin-bottom:15px;"><?= htmlspecialchars($product['title']) ?></h1>

    <div style="display:flex; justify-content:space-between; align-items:center; background:#0d131f; padding:20px; border-radius:12px; margin-bottom:30px;">
        <div>
            <span style="display:block; font-size:0.9rem; color:var(--eafd-color-text-muted);">قیمت محصول:</span>
            <span style="font-size:1.8rem; font-weight:bold; color:var(--eafd-color-secondary);"><?= number_format($product['price']) ?> تومان</span>
        </div>
        <a href="/cart/add/<?= $product['id'] ?>" class="btn btn-primary" style="padding:12px 28px; font-size:1.1rem;">+ افزودن به سبد خرید</a>
    </div>

    <h2 style="font-size:1.3rem; margin-bottom:15px;">توضیحات و مشخصات فنی محصول</h2>
    <div style="line-height:1.8; color:var(--eafd-color-text-muted); margin-bottom:30px;">
        <?= nl2br(htmlspecialchars($product['description'])) ?>
    </div>
</div>
