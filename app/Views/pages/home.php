<!-- Hero Section (Pure Geometric & Typography) -->
<section style="padding: 4rem 0 3rem; text-align: center; position: relative;">
    <div class="container" style="max-width: 900px;">
        <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(0, 240, 255, 0.08); border: 1px solid rgba(0, 240, 255, 0.2); padding: 0.4rem 1rem; border-radius: 50px; font-size: 0.85rem; color: var(--color-primary); margin-bottom: 1.5rem;">
            <span style="display: inline-block; width: 8px; height: 8px; background: var(--color-primary); border-radius: 50%;"></span>
            سیستم وب اختصاصی، بدون تصویر، سرعت ۱۰۰٪
        </div>

        <h1 style="font-size: 2.75rem; font-weight: 800; line-height: 1.3; color: var(--color-text); margin-bottom: 1.5rem;">
            طراحی، توسعه و رشد زیر ساختار یک <span style="color: var(--color-primary);">سیستم واحد و اختصاصی</span>
        </h1>

        <p style="font-size: 1.1rem; color: var(--color-text-muted); line-height: 1.8; margin-bottom: 2rem;">
            EAFD صرفاً یک وب‌سایت شرکتی معمولی نیست؛ سامانه هوشمند و مهندسی‌شده‌ای است برای کسب‌وکارهایی که به دنبال بالاترین کارایی، امنیت، سئو و قابلیت تعامل با ایجنت‌های هوش مصنوعی هستند.
        </p>

        <div style="display: flex; gap: 1rem; justify-content: center;">
            <a href="/start-project" class="btn btn-primary" style="padding: 0.875rem 2rem; font-size: 1rem;">شروع پروژه اختصاصی ←</a>
            <a href="/audit" class="btn btn-outline" style="padding: 0.875rem 2rem; font-size: 1rem;">تحلیل آنلاین سلامت سایت</a>
        </div>
    </div>
</section>

<!-- System Pillars (5 Core Standards) -->
<section style="padding: 3rem 0; border-top: 1px solid var(--color-surface-border); border-bottom: 1px solid var(--color-surface-border); background: var(--color-surface);">
    <div class="container">
        <h2 style="text-align: center; font-size: 1.5rem; margin-bottom: 2.5rem; color: var(--color-text);">پنج استاندارد قطعی در سیستم EAFD</h2>

        <div class="grid grid-cols-4" style="gap: 1.5rem;">
            <div style="text-align: center; padding: 1rem;">
                <div style="font-size: 2rem; font-weight: 800; color: var(--color-primary);">100/100</div>
                <div style="font-weight: 700; margin: 0.5rem 0; color: var(--color-text);">Performance</div>
                <p style="font-size: 0.85rem; color: var(--color-text-muted);">بدون تصویر و کد اضافی، بارگذاری آنی در کسر ثانیه.</p>
            </div>
            <div style="text-align: center; padding: 1rem;">
                <div style="font-size: 2rem; font-weight: 800; color: var(--color-primary);">100/100</div>
                <div style="font-weight: 700; margin: 0.5rem 0; color: var(--color-text);">Accessibility</div>
                <p style="font-size: 0.85rem; color: var(--color-text-muted);">دسترسی‌پذیری کامل طبق استاندارد WCAG 2.2 AA.</p>
            </div>
            <div style="text-align: center; padding: 1rem;">
                <div style="font-size: 2rem; font-weight: 800; color: var(--color-primary);">100/100</div>
                <div style="font-weight: 700; margin: 0.5rem 0; color: var(--color-text);">SEO & Schema</div>
                <p style="font-size: 0.85rem; color: var(--color-text-muted);">ساختار هوشمند داده‌ها برای رتبه‌گیری عالی در گوگل.</p>
            </div>
            <div style="text-align: center; padding: 1rem;">
                <div style="font-size: 2rem; font-weight: 800; color: var(--color-accent);">100%</div>
                <div style="font-weight: 700; margin: 0.5rem 0; color: var(--color-text);">Agentic Browsing</div>
                <p style="font-size: 0.85rem; color: var(--color-text-muted);">قابل فهم و پردازش مستقیم برای ایجنت‌های هوش مصنوعی.</p>
            </div>
        </div>
    </div>
</section>

<!-- Core Services Grid -->
<section style="padding: 4rem 0;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h2 style="font-size: 2rem; color: var(--color-text);">خدمات تخصصی EAFD</h2>
            <p style="color: var(--color-text-muted);">راه‌کارهای جامع مهندسی وب برای رشد کسب‌وکار شما</p>
        </div>

        <div class="grid grid-cols-3">
            <?php foreach ($services as $srv): ?>
                <div class="card">
                    <div style="margin-bottom: 1rem;"><?= $srv['icon_svg'] ?></div>
                    <h3 style="font-size: 1.2rem; color: var(--color-text); margin-bottom: 0.75rem;"><?= \App\Core\Security::escape($srv['title']) ?></h3>
                    <p style="font-size: 0.875rem; color: var(--color-text-muted); line-height: 1.7; margin-bottom: 1.5rem;"><?= \App\Core\Security::escape($srv['short_description']) ?></p>
                    <a href="/services/<?= \App\Core\Security::escape($srv['slug']) ?>" style="font-size: 0.85rem; font-weight: 600; color: var(--color-primary);">مشاهده جزئیات خدمت ←</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<?php if (!empty($faqs)): ?>
<section style="padding: 4rem 0; background: var(--color-surface); border-top: 1px solid var(--color-surface-border);">
    <div class="container" style="max-width: 800px;">
        <h2 style="text-align: center; font-size: 1.75rem; margin-bottom: 2.5rem; color: var(--color-text);">سوالات متداول درباره EAFD</h2>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <?php foreach ($faqs as $faq): ?>
                <div style="background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-lg); padding: 1.25rem;">
                    <h3 style="font-size: 1rem; color: var(--color-primary); margin-bottom: 0.5rem;"><?= \App\Core\Security::escape($faq['question']) ?></h3>
                    <p style="font-size: 0.875rem; color: var(--color-text-muted); margin: 0; line-height: 1.7;"><?= \App\Core\Security::escape($faq['answer']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
