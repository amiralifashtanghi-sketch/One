<div class="card cta-component" style="background: linear-gradient(135deg, var(--eafd-color-surface) 0%, #162032 100%); border:1px solid var(--eafd-color-primary); text-align:center; padding:50px 20px;">
    <h2 style="font-size:2rem; margin-bottom:15px;"><?= htmlspecialchars($settings['title'] ?? 'آماده تحول در سامانه وب خود هستید؟') ?></h2>
    <p style="max-width:600px; margin:0 auto 30px auto; font-size:1.1rem;">تیم EAFD آماده طراحی و پیاده‌سازی پلتفرم اختصاصی شما با بالاترین سرعت و ایمنی می‌باشد.</p>
    <a href="<?= htmlspecialchars($settings['btn_url'] ?? '/contact') ?>" class="btn btn-primary" style="font-size:1.1rem; padding:15px 35px;">
        <?= htmlspecialchars($settings['btn_text'] ?? 'درخواست مشاوره رایگان') ?> ←
    </a>
</div>
