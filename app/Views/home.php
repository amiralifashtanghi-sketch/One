<?php if (empty($sections)): ?>
    <?php \App\Core\View::partial('components/hero', ['settings' => ['title' => 'پلتفرم وب اختصاصی EAFD', 'subtitle' => 'طراحی و معماری وب‌سایت‌های فوق‌سریع، ۱۰۰٪ فارسی و منطبق با استانداردهای WCAG 2.2 AA']]); ?>
    <?php \App\Core\View::partial('components/services', ['settings' => ['title' => 'خدمات تخصصی ما']]); ?>
    <?php \App\Core\View::partial('components/process', ['settings' => ['title' => 'مراحل اجرای پروژه']]); ?>
    <?php \App\Core\View::partial('components/projects', ['settings' => ['title' => 'نمونه پروژه‌های شاخص']]); ?>
    <?php \App\Core\View::partial('components/lab', ['settings' => ['title' => 'آزمایشگاه و ابزارهای آنلاین EAFD']]); ?>
    <?php \App\Core\View::partial('components/cta', ['settings' => ['title' => 'آماده تحول در سامانه وب خود هستید؟']]); ?>
<?php else: ?>
    <?php foreach ($sections as $sec):
        $settings = json_decode($sec['settings'] ?? '{}', true);
        $type = $sec['section_type'];
    ?>
        <?php ob_start(); ?>
            <?php \App\Core\View::partial("components/{$type}", ['settings' => $settings]); ?>
        <?php $compContent = ob_get_clean(); ?>

        <?php \App\Core\View::partial('components/section', [
            'id' => "sec-{$sec['id']}",
            'content' => $compContent,
        ]); ?>
    <?php endforeach; ?>
<?php endif; ?>
