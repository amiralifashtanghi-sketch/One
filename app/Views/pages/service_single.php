<div class="container" style="max-width: 850px; padding: 2rem 0;">
    <!-- Breadcrumb -->
    <nav aria-label="مسیر صفحه" style="font-size: 0.85rem; margin-bottom: 1.5rem; color: var(--color-text-dim);">
        <a href="/" style="color: var(--color-text-muted);">صفحه اصلی</a> /
        <a href="/services" style="color: var(--color-text-muted);">خدمات</a> /
        <span style="color: var(--color-primary);"><?= \App\Core\Security::escape($service['title']) ?></span>
    </nav>

    <!-- Header -->
    <div style="margin-bottom: 2.5rem; border-bottom: 1px solid var(--color-surface-border); padding-bottom: 2rem;">
        <div style="margin-bottom: 1rem;"><?= $service['icon_svg'] ?></div>
        <h1 style="font-size: 2.25rem; color: var(--color-text); margin-bottom: 1rem;"><?= \App\Core\Security::escape($service['title']) ?></h1>
        <p style="font-size: 1.1rem; color: var(--color-primary); line-height: 1.8; margin-bottom: 0;"><?= \App\Core\Security::escape($service['short_description']) ?></p>
    </div>

    <!-- Main Semantic Content -->
    <article style="line-height: 2; font-size: 1rem; color: var(--color-text); margin-bottom: 3rem;">
        <?= nl2br(\App\Core\Security::escape($service['full_description'])) ?>
    </article>

    <!-- Service Call to Action -->
    <div class="card" style="background: linear-gradient(135deg, var(--color-surface), #131a26); text-align: center; padding: 2.5rem;">
        <h2 style="font-size: 1.5rem; color: var(--color-text); margin-bottom: 0.75rem;">آیا مایل به استفاده از این خدمت در پروژه خود هستید؟</h2>
        <p style="font-size: 0.9rem; color: var(--color-text-muted); margin-bottom: 1.5rem;">با ثبت درخواست در پیکربندی پروژه، مشاوره تخصصی و برآورد زمان‌بندی را دریافت کنید.</p>
        <a href="/start-project" class="btn btn-primary" style="padding: 0.75rem 2rem;">سفارش خدمت <?= \App\Core\Security::escape($service['title']) ?> ←</a>
    </div>
</div>
