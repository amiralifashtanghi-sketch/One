<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پنل مدیریت اختصاصی EAFD</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body style="background:var(--eafd-color-bg); color:var(--eafd-color-text);">
    <div style="display:flex; min-height:100vh;">
        <?php \App\Core\View::partial('admin/partials/sidebar'); ?>

        <div style="flex:1; padding:30px;">
            <?php if ($flashSuccess = \App\Core\Session::flash('success')): ?>
                <div class="alert alert-success" style="background:#14532d; color:#86efac; padding:15px; border-radius:8px; margin-bottom:20px; font-weight:bold;"><?= htmlspecialchars($flashSuccess) ?></div>
            <?php endif; ?>
            <?php if ($flashError = \App\Core\Session::flash('error')): ?>
                <div class="alert alert-danger" style="background:#7f1d1d; color:#fca5a5; padding:15px; border-radius:8px; margin-bottom:20px; font-weight:bold;"><?= htmlspecialchars($flashError) ?></div>
            <?php endif; ?>

            <?= $content ?>
        </div>
    </div>
</body>
</html>
