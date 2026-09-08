<div class="container" style="padding: 60px 20px;">
    <div style="max-width: 900px; margin: 0 auto;">
        <h1 style="font-size: 2.2rem; margin-bottom: 24px; color: var(--eafd-color-text-emphasis);"><?= htmlspecialchars($page['title'] ?? '') ?></h1>
        <div class="page-content" style="font-size: 1.05rem; line-height: 1.8; color: var(--eafd-color-text-muted);">
            <?= $page['content'] ?? '' ?>
        </div>
    </div>
</div>
