<div class="page-header" style="text-align:center; margin-bottom:40px;">
    <h1>تاریخچه سفارشات شما</h1>
</div>

<div class="card" style="max-width:850px; margin:0 auto;">
    <table class="install-table" style="width:100%;">
        <thead>
            <tr>
                <th>شماره سفارش</th>
                <th>مبلغ کل</th>
                <th>درگاه پرداخت</th>
                <th>وضعیت</th>
                <th>تاریخ ثبت</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($orders)): ?>
                <tr><td colspan="5" style="text-align:center;">هنوز هیچ سفارشی ثبت نکرده‌اید.</td></tr>
            <?php else: ?>
                <?php foreach ($orders as $ord): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($ord['order_number']) ?></strong></td>
                        <td><?= number_format($ord['total_amount']) ?> تومان</td>
                        <td><code><?= htmlspecialchars($ord['gateway']) ?></code></td>
                        <td>
                            <?php if ($ord['status'] === 'completed'): ?>
                                <span class="badge badge-success">تکمیل‌شده</span>
                            <?php else: ?>
                                <span class="badge badge-danger"><?= htmlspecialchars($ord['status']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($ord['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
