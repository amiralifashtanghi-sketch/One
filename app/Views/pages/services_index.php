<div class="container" style="padding: 2rem 0;">
    <div style="text-align: center; margin-bottom: 3rem;">
        <h1 style="font-size: 2.25rem; color: var(--color-text);">خدمات تخصصی مهندسی دیجیتال EAFD</h1>
        <p style="color: var(--color-text-muted); max-width: 650px; margin: 0.5rem auto 0;">راه‌کارهای ساختاری، توسعه وب اختصاصی، سئو و رشد بدون وابستگی‌های سنگین</p>
    </div>

    <div class="grid grid-cols-2" style="gap: 2rem;">
        <?php foreach ($services as $srv): ?>
            <div class="card" style="padding: 2rem;">
                <div style="margin-bottom: 1rem;"><?= $srv['icon_svg'] ?></div>
                <h2 style="font-size: 1.35rem; color: var(--color-text); margin-bottom: 0.75rem;"><?= \App\Core\Security::escape($srv['title']) ?></h2>
                <p style="font-size: 0.9rem; color: var(--color-text-muted); line-height: 1.8; margin-bottom: 1.5rem;"><?= \App\Core\Security::escape($srv['short_description']) ?></p>
                <a href="/services/<?= \App\Core\Security::escape($srv['slug']) ?>" class="btn btn-outline" style="font-size: 0.85rem;">مطالعه ساختار و معرفی خدمت ←</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
