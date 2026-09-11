<div class="projects-component">
    <div style="text-align:center; margin-bottom:40px;">
        <h2><?= htmlspecialchars($settings['title'] ?? 'نمونه پروژه‌های شاخص EAFD') ?></h2>
        <p>اثبات توانمندی فنی در پروژه‌های واقعی با سرعت و کارایی فوق‌العاده</p>
    </div>

    <?php $projectsList = \App\Core\Database::fetchAll("SELECT * FROM projects WHERE is_active = 1 ORDER BY sort_order ASC"); ?>

    <div class="grid grid-cols-2">
        <?php foreach ($projectsList as $prj): ?>
            <div class="card">
                <span class="badge badge-success" style="margin-bottom:10px; display:inline-block;"><?= htmlspecialchars($prj['client_name']) ?></span>
                <h3 style="font-size:1.3rem; margin-bottom:10px;"><?= htmlspecialchars($prj['title']) ?></h3>
                <p style="font-size:0.95rem; margin-bottom:15px;"><?= htmlspecialchars($prj['summary']) ?></p>
                <p style="font-size:0.85rem; color:var(--eafd-color-secondary); margin-bottom:20px;">تکنولوژی‌ها: <?= htmlspecialchars($prj['technologies']) ?></p>
                <a href="/projects/<?= htmlspecialchars($prj['slug']) ?>" class="btn btn-secondary" style="width:100%;">مشاهده Case Study کامل ←</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
