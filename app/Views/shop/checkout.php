<div class="page-header" style="text-align:center; margin-bottom:40px;">
    <h1>تسویه‌حساب و پرداخت آنلاین</h1>
    <p>اطلاعات سفارش خود را بررسی و درگاه پرداخت مورد نظر را انتخاب نمایید</p>
</div>

<div class="card" style="max-width:800px; margin:0 auto;">
    <h2 style="font-size:1.3rem; margin-bottom:20px; border-bottom:1px solid var(--eafd-color-border); padding-bottom:10px;">خلاصه اقلام سفارش</h2>

    <ul style="list-style:none; padding:0; margin:0 0 30px 0;">
        <?php foreach ($items as $item): ?>
            <li style="display:flex; justify-content:space-between; padding:12px 0; border-bottom:1px solid var(--eafd-color-border);">
                <span><?= htmlspecialchars($item['title']) ?> (<?= $item['quantity'] ?> عدد)</span>
                <strong style="color:var(--eafd-color-secondary);"><?= number_format($item['price'] * $item['quantity']) ?> تومان</strong>
            </li>
        <?php endforeach; ?>
    </ul>

    <div style="background:#0d131f; padding:20px; border-radius:12px; margin-bottom:30px; display:flex; justify-content:space-between; align-items:center;">
        <span style="font-size:1.1rem; font-weight:bold;">مبلغ کل قابل پرداخت:</span>
        <span style="font-size:1.6rem; font-weight:bold; color:var(--eafd-color-secondary);"><?= number_format($total) ?> تومان</span>
    </div>

    <form method="POST" action="/checkout/process">
        <input type="hidden" name="_csrf_token" value="<?= \App\Core\Csrf::generate() ?>">

        <h3 style="font-size:1.1rem; margin-bottom:15px;">انتخاب درگاه پرداخت:</h3>
        <div style="display:flex; flex-direction:column; gap:12px; margin-bottom:30px;">
            <label class="card" style="padding:15px; cursor:pointer; display:flex; align-items:center; gap:12px;">
                <input type="radio" name="gateway" value="mock" checked>
                <div>
                    <strong>درگاه شبیه‌ساز آزمایشی EAFD (Mock Sandbox)</strong>
                    <small style="display:block; color:var(--eafd-color-text-muted);">تست آنی پرداخت و صدور اتوماتیک لایسنس بدون کسر وجه</small>
                </div>
            </label>

            <label class="card" style="padding:15px; cursor:pointer; display:flex; align-items:center; gap:12px;">
                <input type="radio" name="gateway" value="zarinpal">
                <div>
                    <strong>درگاه پرداخت زرین‌پال (ZarinPal)</strong>
                    <small style="display:block; color:var(--eafd-color-text-muted);">اتصال مستقیم به درگاه پرداخت رسمی زرین‌پال</small>
                </div>
            </label>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%; padding:15px; font-size:1.1rem;">تایید و انتقال به درگاه پرداخت ←</button>
    </form>
</div>
