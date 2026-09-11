<?php
/**
 * Hero Component - Editorial Dark Premium Layout
 */
$primaryCtaText = $settings['primary_cta_text'] ?? ($settings['cta_text'] ?? 'شروع یک پروژه');
$primaryCtaUrl  = $settings['primary_cta_url'] ?? ($settings['cta_url'] ?? '/projects');
$secondaryCtaText = $settings['secondary_cta_text'] ?? 'مشاهده پروژه‌ها';
$secondaryCtaUrl  = $settings['secondary_cta_url'] ?? '/projects';
?>
<section class="eafd-hero-section">
    <div class="container eafd-hero-container">
        <div class="eafd-hero-content">
            <h1 class="eafd-hero-title">
                <span class="eafd-hero-line-1">وب‌سایت نمی‌سازیم؛</span>
                <span class="eafd-hero-line-2">سیستم دیجیتال می‌سازیم.</span>
            </h1>

            <p class="eafd-hero-description">
                <?= htmlspecialchars($settings['subtitle'] ?? 'معماری و مهندسی سیستم‌های وب پیشرفته با بالاترین استاندارد کارایی، دسترس‌پذیری و طراحی سیستماتیک.') ?>
            </p>

            <div class="eafd-hero-actions">
                <a href="<?= htmlspecialchars($primaryCtaUrl) ?>" class="eafd-btn-primary">
                    <?= htmlspecialchars($primaryCtaText) ?>
                    <?php \App\Core\View::partial('components/icons/arrow'); ?>
                </a>

                <a href="<?= htmlspecialchars($secondaryCtaUrl) ?>" class="eafd-btn-secondary">
                    <?= htmlspecialchars($secondaryCtaText) ?>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
.eafd-hero-section {
    padding: var(--eafd-spacing-xxl) 0 var(--eafd-spacing-xl) 0;
    position: relative;
}

.eafd-hero-container {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.eafd-hero-content {
    max-width: 860px;
}

.eafd-hero-title {
    display: flex;
    flex-direction: column;
    gap: var(--eafd-spacing-xs);
    font-size: clamp(2.2rem, 5.5vw, 4rem);
    font-weight: 800;
    line-height: 1.15;
    margin-bottom: var(--eafd-spacing-lg);
    letter-spacing: -0.03em;
}

.eafd-hero-line-1 {
    color: var(--eafd-color-text);
}

.eafd-hero-line-2 {
    color: var(--eafd-color-text-muted);
}

.eafd-hero-description {
    font-size: clamp(1rem, 2vw, 1.25rem);
    color: var(--eafd-color-text-muted);
    line-height: 1.6;
    margin-bottom: var(--eafd-spacing-xl);
    max-width: 680px;
}

.eafd-hero-actions {
    display: flex;
    align-items: center;
    gap: var(--eafd-spacing-md);
    flex-wrap: wrap;
}

@media (max-width: 640px) {
    .eafd-hero-section {
        padding: var(--eafd-spacing-xl) 0;
    }
    .eafd-hero-actions {
        width: 100%;
        flex-direction: column;
        align-items: stretch;
    }
    .eafd-hero-actions .eafd-btn-primary,
    .eafd-hero-actions .eafd-btn-secondary {
        width: 100%;
        justify-content: center;
    }
}
</style>
