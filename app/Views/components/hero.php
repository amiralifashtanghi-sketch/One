<div class="hero-component" style="text-align:center; padding:60px 0;">
    <span class="badge badge-success" style="margin-bottom:20px; font-size:0.95rem; display:inline-block;">⚡ معمار سامانه‌های وب اختصاصی</span>
    <h1 style="font-size: clamp(2rem, 5vw, 3.5rem); margin-bottom:20px; color:var(--eafd-color-text);">
        <?= htmlspecialchars($settings['title'] ?? 'پلتفرم وب اختصاصی EAFD') ?>
    </h1>
    <p style="font-size:1.2rem; max-width:800px; margin:0 auto 35px auto; color:var(--eafd-color-text-muted);">
        <?= htmlspecialchars($settings['subtitle'] ?? 'طراحی و معماری سیستم‌های فوق‌سریع، ۱۰۰٪ فارسی و منطبق با استانداردهای WCAG 2.2 AA') ?>
    </p>
    <div style="display:flex; justify-content:center; gap:15px; flex-wrap:wrap;">
        <a href="<?= htmlspecialchars($settings['cta_url'] ?? '/contact') ?>" class="btn btn-primary">
            <?= htmlspecialchars($settings['cta_text'] ?? 'شروع پروژه') ?> ←
        </a>
        <a href="/services" class="btn btn-secondary">مشاهده خدمات تخصصی</a>
    </div>
</div>
