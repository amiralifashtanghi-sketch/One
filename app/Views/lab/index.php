<div class="page-header" style="text-align:center; margin-bottom:40px;">
    <h1>آزمایشگاه و ابزارهای آنلاین EAFD (LAB)</h1>
    <p>مجموعه ابزارهای آنلاین محاسباتی و آزمون‌های ارزیابی کیفیت سامانه‌های وب</p>
</div>

<div class="grid grid-cols-2" style="gap:30px;">
    <!-- Tools Section -->
    <div class="card">
        <h2 style="color:var(--eafd-color-secondary); font-size:1.4rem; margin-bottom:20px;">🎨 ابزارهای محاسباتی آنلاین</h2>
        <?php foreach ($tools as $tool): ?>
            <div style="border-bottom:1px solid var(--eafd-color-border); padding:15px 0;">
                <h3 style="font-size:1.1rem; margin-bottom:5px;"><?= htmlspecialchars($tool['title']) ?></h3>
                <p style="font-size:0.9rem; margin-bottom:10px;"><?= htmlspecialchars($tool['description']) ?></p>
                <a href="/lab/tool/<?= htmlspecialchars($tool['slug']) ?>" class="btn btn-secondary" style="padding:6px 14px; font-size:0.85rem;">اجرای ابزار ←</a>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Quizzes Section -->
    <div class="card">
        <h2 style="color:var(--eafd-color-secondary); font-size:1.4rem; margin-bottom:20px;">🧪 آزمون‌های سنجش و ارزیابی</h2>
        <?php foreach ($quizzes as $quiz): ?>
            <div style="border-bottom:1px solid var(--eafd-color-border); padding:15px 0;">
                <h3 style="font-size:1.1rem; margin-bottom:5px;"><?= htmlspecialchars($quiz['title']) ?></h3>
                <p style="font-size:0.9rem; margin-bottom:10px;"><?= htmlspecialchars($quiz['description']) ?></p>
                <a href="/lab/quiz/<?= htmlspecialchars($quiz['slug']) ?>" class="btn btn-secondary" style="padding:6px 14px; font-size:0.85rem;">شروع آزمون ←</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
