<div class="page-header" style="text-align:center; margin-bottom:40px;">
    <h1>حساب کاربری: <?= htmlspecialchars($user['name']) ?></h1>
    <p>مدیریت سفارشات، کلیدهای لایسنس فعال و دانلود محصولات خریداریشده</p>
</div>

<div class="grid grid-cols-2" style="gap:30px;">
    <div class="card">
        <h2 style="font-size:1.3rem; margin-bottom:15px; color:var(--eafd-color-secondary);">اطلاعات حساب کاربری</h2>
        <p><strong>نام و خانوادگی:</strong> <?= htmlspecialchars($user['name']) ?></p>
        <p><strong>شماره موبایل:</strong> <?= htmlspecialchars($user['phone']) ?></p>
        <p><strong>پست الکترونیک:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>نقش کاربری:</strong> <?= htmlspecialchars($user['role']) ?></p>
        <a href="/logout" class="btn btn-secondary" style="margin-top:15px; color:#fca5a5;">خروج از حساب کاربری</a>
    </div>

    <div class="card">
        <h2 style="font-size:1.3rem; margin-bottom:15px; color:var(--eafd-color-secondary);">خلاصه فعالیت‌ها</h2>
        <p><strong>تعداد سفارشات:</strong> <?= count($orders) ?> سفارش</p>
        <p><strong>تعداد لایسنس‌های فعال:</strong> <?= count($licenses) ?> لایسنس</p>
        <div style="display:flex; gap:10px; margin-top:20px;">
            <a href="/account/orders" class="btn btn-primary" style="flex:1;">مشاهده سفارشات</a>
            <a href="/account/licenses" class="btn btn-secondary" style="flex:1;">مشاهده لایسنس‌ها</a>
        </div>
    </div>
</div>
