<div class="page-header" style="text-align:center; margin-bottom:40px;">
    <h1>نمونه پروژه‌های شاخص EAFD (Portfolio)</h1>
    <p>بررسی نمونه پروژه‌های اجرایی کاملاً تصویر-آزاد (Image-Free Design System) با بالاترین کارایی</p>
</div>

<div class="grid grid-cols-2">
    <?php foreach ($projects as $prj): ?>
        <div class="card">
            <span class="badge badge-success" style="margin-bottom:12px; display:inline-block;"><?= htmlspecialchars($prj['client_name']) ?></span>
            <h2 style="font-size:1.4rem; margin-bottom:10px;"><?= htmlspecialchars($prj['title']) ?></h2>
            <p style="font-size:0.95rem; margin-bottom:15px;"><?= htmlspecialchars($prj['summary']) ?></p>
            <p style="font-size:0.85rem; color:var(--eafd-color-secondary); margin-bottom:20px;">تکنولوژی‌ها: <?= htmlspecialchars($prj['technologies']) ?></p>
            <a href="/projects/<?= htmlspecialchars($prj['slug']) ?>" class="btn btn-secondary" style="width:100%;">مشاهده Case Study کامل ←</a>
        </div>
    <?php endforeach; ?>
</div>
