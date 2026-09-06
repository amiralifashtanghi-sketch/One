<div class="page-header" style="margin-bottom:30px;">
    <h1 style="margin:0;">داشبورد مدیریتی EAFD</h1>
    <p style="margin:5px 0 0 0; color:var(--eafd-color-text-muted);">خلاصه وضعیت کارکرد سامانه، درآمد، سفارشات و لایسنس‌ها</p>
</div>

<div class="grid grid-cols-4" style="margin-bottom:30px;">
    <div class="card" style="border-right:4px solid var(--eafd-color-primary);">
        <span style="font-size:0.9rem; color:var(--eafd-color-text-muted);">درآمد کل حاصل از فروش</span>
        <h2 style="font-size:1.6rem; margin:10px 0 0 0; color:var(--eafd-color-secondary);"><?= number_format($revenue) ?> تومان</h2>
    </div>
    <div class="card" style="border-right:4px solid var(--eafd-color-secondary);">
        <span style="font-size:0.9rem; color:var(--eafd-color-text-muted);">تعداد کل سفارشات</span>
        <h2 style="font-size:1.6rem; margin:10px 0 0 0;"><?= $ordersCount ?> سفارش</h2>
    </div>
    <div class="card" style="border-right:4px solid var(--eafd-color-accent);">
        <span style="font-size:0.9rem; color:var(--eafd-color-text-muted);">لایسنس‌های صادر شده</span>
        <h2 style="font-size:1.6rem; margin:10px 0 0 0;"><?= $licensesCount ?> لایسنس</h2>
    </div>
    <div class="card" style="border-right:4px solid #86efac;">
        <span style="font-size:0.9rem; color:var(--eafd-color-text-muted);">تعداد کاربران</span>
        <h2 style="font-size:1.6rem; margin:10px 0 0 0;"><?= $usersCount ?> کاربر</h2>
    </div>
</div>

<div class="card">
    <h2 style="font-size:1.2rem; margin-bottom:20px;">آخرین سفارشات ثبت‌شده</h2>
    <table class="install-table" style="width:100%;">
        <thead>
            <tr>
                <th>شماره سفارش</th>
                <th>نام خریدار</th>
                <th>مبلغ کل</th>
                <th>وضعیت</th>
                <th>تاریخ</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($recentOrders)): ?>
                <tr><td colspan="5" style="text-align:center;">هنوز سفارشی ثبت نشده است.</td></tr>
            <?php else: ?>
                <?php foreach ($recentOrders as $ord): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($ord['order_number']) ?></strong></td>
                        <td><?= htmlspecialchars($ord['user_name']) ?></td>
                        <td><?= number_format($ord['total_amount']) ?> تومان</td>
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
