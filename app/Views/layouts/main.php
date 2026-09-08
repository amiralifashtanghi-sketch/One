<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= \App\Helpers\SeoHelper::renderMeta($page['title'] ?? 'EAFD', $page['summary'] ?? '') ?>
    <?= \App\Helpers\SeoHelper::renderOrganizationJsonLd() ?>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body class="eafd-grid-subtle">
    <a href="#main-content" class="skip-link">پرش به محتوای اصلی سایت</a>

    <?php \App\Core\View::partial('partials/header'); ?>

    <main id="main-content" style="flex:1;">
        <div class="container" style="padding-top: var(--eafd-spacing-lg);">
            <?php if ($flashSuccess = \App\Core\Session::flash('success')): ?>
                <div class="alert alert-success" style="background:#14532d; color:#86efac; padding:15px; border-radius:8px; margin-bottom:20px; font-weight:bold;"><?= htmlspecialchars($flashSuccess) ?></div>
            <?php endif; ?>
            <?php if ($flashError = \App\Core\Session::flash('error')): ?>
                <div class="alert alert-danger" style="background:#7f1d1d; color:#fca5a5; padding:15px; border-radius:8px; margin-bottom:20px; font-weight:bold;"><?= htmlspecialchars($flashError) ?></div>
            <?php endif; ?>

            <?= $content ?>
        </div>
    </main>

    <?php \App\Core\View::partial('components/footer'); ?>
</body>
</html>
