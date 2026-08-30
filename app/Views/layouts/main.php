<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= \App\Core\Security::escape($pageTitle ?? 'EAFD — سیستم اختصاصی مهندسی دیجیتال و رشد') ?></title>
    <meta name="description" content="<?= \App\Core\Security::escape($metaDescription ?? 'EAFD سیستم اختصاصی طراحی سایت، سئو، و دیجیتال مارکتینگ بدون وابستگی به سرویس‌های خارجی و بدون تصویر.') ?>">
    <link rel="canonical" href="<?= \App\Core\Security::escape($canonicalUrl ?? 'http://localhost' . $_SERVER['REQUEST_URI']) ?>">
    <link rel="stylesheet" href="/assets/css/style.css">

    <!-- Structured Data (JSON-LD) for SEO & Agentic Crawlers -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "Organization",
          "@id": "http://localhost/#organization",
          "name": "EAFD",
          "url": "http://localhost",
          "logo": "http://localhost/assets/icon.svg",
          "description": "سیستم اختصاصی مهندسی وب، سئو و رشد دیجیتال بدون نیاز به تصویر و با کارایی ۱۰۰٪",
          "knowsAbout": ["طراحی سایت اختصاصی", "سئو ساختاری", "توسعه قالب وردپرس", "Agentic Browsing"]
        },
        {
          "@type": "WebSite",
          "@id": "http://localhost/#website",
          "url": "http://localhost",
          "name": "EAFD Digital System",
          "publisher": {"@id": "http://localhost/#organization"},
          "inLanguage": "fa-IR"
        }
      ]
    }
    </script>

    <?php \App\Core\Security::setSecurityHeaders(); ?>
</head>
<body>
    <a href="#main-content" class="skip-link">پرش به محتوای اصلی</a>

    <!-- Top Announcement Bar -->
    <div style="background: var(--color-surface); border-bottom: 1px solid var(--color-surface-border); padding: 0.5rem 0; font-size: var(--font-size-xs); text-align: center; color: var(--color-primary);">
        <span>طراحی، توسعه و رشد دیجیتال زیر یک سیستم واحد و اختصاصی</span>
    </div>

    <!-- Header / Navigation -->
    <header style="background: rgba(14, 18, 26, 0.85); backdrop-filter: blur(10px); border-bottom: 1px solid var(--color-surface-border); position: sticky; top: 0; z-index: 1000;">
        <div class="container" style="display: flex; align-items: center; justify-content: space-between; height: 70px;">
            <!-- Logo (Pure SVG Geometric) -->
            <a href="/" aria-label="EAFD - صفحه اصلی" style="display: flex; align-items: center; gap: 0.75rem;">
                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <rect width="36" height="36" rx="8" fill="#0E121A" stroke="#00F0FF" stroke-width="1.5"/>
                    <path d="M10 12H26M10 18H22M10 24H26" stroke="#00F0FF" stroke-width="2.5" stroke-linecap="round"/>
                    <circle cx="26" cy="18" r="2.5" fill="#FF8A00"/>
                </svg>
                <span style="font-size: 1.25rem; font-weight: 800; letter-spacing: -0.5px; color: var(--color-text);">EAFD</span>
            </a>

            <!-- Navigation Links -->
            <nav aria-label="منوی اصلی">
                <ul style="display: flex; list-style: none; gap: 1.5rem; align-items: center;">
                    <li><a href="/" style="color: var(--color-text); font-weight: 500;">صفحه اصلی</a></li>
                    <li><a href="/services" style="color: var(--color-text-muted); font-weight: 500;">خدمات اختصاصی</a></li>
                    <li><a href="/portfolio" style="color: var(--color-text-muted); font-weight: 500;">نمونه‌کارها</a></li>
                    <li><a href="/lab" style="color: var(--color-text-muted); font-weight: 500;">آزمایشگاه (LAB)</a></li>
                    <li><a href="/audit" style="color: var(--color-text-muted); font-weight: 500;">تحلیل آنلاین سایت</a></li>
                </ul>
            </nav>

            <!-- Main Call To Action -->
            <div>
                <a href="/start-project" class="btn btn-primary" aria-label="شروع پروژه جدید">شروع پروژه</a>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main id="main-content" style="flex: 1; padding: var(--spacing-12) 0;">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer style="background: var(--color-surface); border-top: 1px solid var(--color-surface-border); padding: var(--spacing-12) 0; margin-top: var(--spacing-16);">
        <div class="container grid grid-cols-4">
            <div>
                <h3 style="font-size: var(--font-size-lg); color: var(--color-primary);">درباره EAFD</h3>
                <p style="font-size: var(--font-size-sm); color: var(--color-text-muted);">
                    EAFD یک سیستم اختصاصی مهندسی وب، سئو و رشد دیجیتال است که بدون وابستگی به فریم‌ورک‌های سنگین و بدون نیاز به تصویر، عملکرد ۱۰۰٪ ارائه می‌دهد.
                </p>
            </div>
            <div>
                <h3 style="font-size: var(--font-size-lg); color: var(--color-text);">خدمات اصلی</h3>
                <ul style="list-style: none; font-size: var(--font-size-sm); line-height: 2;">
                    <li><a href="/services/web-design">طراحی سایت اختصاصی</a></li>
                    <li><a href="/services/seo">سئو و بهینه‌سازی موتورهای جستجو</a></li>
                    <li><a href="/services/digital-marketing">دیجیتال مارکتینگ و فروش آنلاین</a></li>
                    <li><a href="/services/wordpress-theme">توسعه قالب اختصاصی وردپرس</a></li>
                </ul>
            </div>
            <div>
                <h3 style="font-size: var(--font-size-lg); color: var(--color-text);">ابزارها و آزمایشگاه</h3>
                <ul style="list-style: none; font-size: var(--font-size-sm); line-height: 2;">
                    <li><a href="/audit">تحلیل هوشمند سلامت سایت</a></li>
                    <li><a href="/lab">پروژه‌های آزمایشگاهی LAB</a></li>
                    <li><a href="/start-project">فرم پیکربندی پروژه</a></li>
                </ul>
            </div>
            <div>
                <h3 style="font-size: var(--font-size-lg); color: var(--color-text);">اطلاعات سیستم</h3>
                <p style="font-size: var(--font-size-xs); color: var(--color-text-dim);">
                    نسخه سیستم: ۱.۰.۰<br>
                    وضعیت سرور: فعال و امن<br>
                    معماری: PHP 8.2+ MVC Native
                </p>
            </div>
        </div>
        <div class="container" style="border-top: 1px solid var(--color-surface-border); margin-top: var(--spacing-8); padding-top: var(--spacing-6); text-align: center; font-size: var(--font-size-xs); color: var(--color-text-dim);">
            تمامی حقوق مادی و معنوی این سیستم متعلق به EAFD می‌باشد. © <?= date('Y') ?>
        </div>
    </footer>
</body>
</html>
