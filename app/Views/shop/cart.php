<div class="page-header" style="text-align:center; margin-bottom:40px;">
    <h1>سبد خرید شما</h1>
</div>

<div class="card" style="max-width:800px; margin:0 auto;">
    <?php if (empty($items)): ?>
        <div style="text-align:center; padding:30px;">
            <p>سبد خرید شما در حال حاضر خالی می‌باشد.</p>
            <a href="/store" class="btn btn-primary" style="margin-top:15px;">مشاهده فروشگاه محصولات ←</a>
        </div>
    <?php else: ?>
        <table class="install-table" style="width:100%; margin-bottom:25px;">
            <thead>
                <tr>
                    <th>عنوان محصول</th>
                    <th>قیمت واحد</th>
                    <th>تعداد</th>
                    <th>مجموع</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($item['title']) ?></strong></td>
                        <td><?= number_format($item['price']) ?> تومان</td>
                        <td><?= $item['quantity'] ?></td>
                        <td><?= number_format($item['price'] * $item['quantity']) ?> تومان</td>
                        <td><a href="/cart/remove/<?= $item['id'] ?>" style="color:#fca5a5;">حذف</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid var(--eafd-color-border); padding-top:20px;">
            <div>
                <span style="font-size:1.1rem;">جمع کل قابل پرداخت:</span>
                <span style="font-size:1.5rem; font-weight:bold; color:var(--eafd-color-secondary); margin-right:10px;"><?= number_format($total) ?> تومان</span>
            </div>
            <a href="/checkout" class="btn btn-primary" style="padding:12px 30px; font-size:1.1rem;">تکمیل سفارش و پرداخت آنلاین ←</a>
        </div>
    <?php endif; ?>
</div>
