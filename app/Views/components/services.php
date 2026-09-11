<?php
/**
 * Services Component - Editorial & Systematic Design
 * Visual hierarchy: Section Title -> Subtitle -> Service Cards -> Outcome -> CTA
 */
$sectionTitle = $settings['title'] ?? 'خدمات سیستماتیک';
$sectionSubtitle = $settings['subtitle'] ?? 'یک سایت، فقط یک سایت نیست. طراحی بدون استراتژی، فقط ظاهر است.';
$layoutVariant = $settings['layout_variant'] ?? 'grid'; // Options: grid, list, editorial

$servicesList = \App\Core\Database::fetchAll("SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order ASC");

// Default fallback items if DB empty
if (empty($servicesList)) {
    $servicesList = [
        [
            'id' => 1,
            'title' => 'معماری و توسعه اختصاصی وب',
            'summary' => 'طراحی و ساخت زیرساخت‌های کاملاً بومی با PHP و MySQL بدون ابزارهای سنگین و کند.',
            'outcome' => 'خروجی: لود لحظه‌ای، کارایی ۱۰۰٪ و قابلیت مقیاس‌پذیری بالا.',
            'slug' => 'custom-web-architecture',
            'price_start' => 'تخصصی'
        ],
        [
            'id' => 2,
            'title' => 'دیزاین سیستم و رابط کاربری فارسی',
            'summary' => 'خلق سیستم‌های بصری یکپارچه بر پایه تایپوگرافی، Whitespace و UI تیره پریمیوم.',
            'outcome' => 'خروجی: تجربه کاربری بی‌نقص، جهت‌دهی دقیق RTL و accessibility کاملاً استاندارد.',
            'slug' => 'ui-ux-design-system',
            'price_start' => 'سیستماتیک'
        ],
        [
            'id' => 3,
            'title' => 'بهینه‌سازی Core Web Vitals و LCP',
            'summary' => 'ارتقاء امتیازهای عملکرد، دسترس‌پذیری و SEO به ۱۰۰/۱۰۰ واقعی در Lighthouse.',
            'outcome' => 'خروجی: رتبه برتر گوگل، بارگذاری زیر ۱ ثانیه و تجربه کاربری روان.',
            'slug' => 'performance-optimization',
            'price_start' => 'اصولی'
        ]
    ];
}
?>

<section class="eafd-services-section">
    <div class="container">
        <div class="eafd-services-header">
            <h2 class="eafd-services-title"><?= htmlspecialchars($sectionTitle) ?></h2>
            <p class="eafd-services-subtitle"><?= htmlspecialchars($sectionSubtitle) ?></p>
        </div>

        <div class="eafd-services-layout eafd-services-layout-<?= htmlspecialchars($layoutVariant) ?>">
            <?php foreach ($servicesList as $index => $srv): ?>
                <?php
                    $numFormatted = sprintf("%02d", $index + 1);
                    $srvOutcome = $srv['outcome'] ?? ($srv['price_start'] ? 'ویژگی: ' . $srv['price_start'] : 'خروجی: سیستماتیک و استاندارد');
                ?>
                <article class="service-card">
                    <div>
                        <div class="service-card-number"><?= $numFormatted ?></div>
                        <h3 class="service-card-title"><?= htmlspecialchars($srv['title']) ?></h3>
                        <p class="service-card-desc"><?= htmlspecialchars($srv['summary'] ?? $srv['description'] ?? '') ?></p>
                    </div>

                    <div>
                        <div class="service-card-outcome"><?= htmlspecialchars($srvOutcome) ?></div>
                        <a href="/services/<?= htmlspecialchars($srv['slug'] ?? 'detail') ?>" class="eafd-btn-secondary" style="width: 100%; justify-content: space-between;">
                            <span>مشاهده جزئیات</span>
                            <?php \App\Core\View::partial('components/icons/arrow'); ?>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
.eafd-services-section {
    padding: var(--eafd-spacing-xxl) 0;
}

.eafd-services-header {
    margin-bottom: var(--eafd-spacing-xl);
    max-width: 720px;
}

.eafd-services-title {
    font-size: var(--eafd-font-size-h2);
    font-weight: 800;
    color: var(--eafd-color-text);
    margin-bottom: var(--eafd-spacing-xs);
    letter-spacing: -0.02em;
}

.eafd-services-subtitle {
    font-size: clamp(1rem, 1.8vw, 1.2rem);
    color: var(--eafd-color-text-muted);
    line-height: 1.6;
    margin-bottom: 0;
}

.eafd-services-layout-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--eafd-spacing-lg);
}

.eafd-services-layout-list {
    display: flex;
    flex-direction: column;
    gap: var(--eafd-spacing-md);
}

.eafd-services-layout-editorial {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--eafd-spacing-xl);
}

@media (max-width: 1024px) {
    .eafd-services-layout-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 640px) {
    .eafd-services-layout-grid,
    .eafd-services-layout-editorial {
        grid-template-columns: repeat(1, 1fr);
    }
}
</style>
