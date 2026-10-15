<div class="container" style="max-width: 850px; padding: 2rem 0;">
    <div style="margin-bottom: 2.5rem; border-bottom: 1px solid var(--color-surface-border); padding-bottom: 2rem;">
        <h1 style="font-size: 2.25rem; color: var(--color-text); margin-bottom: 1rem;"><?= \App\Core\Security::escape($caseStudy['title']) ?></h1>
        <p style="font-size: 1.1rem; color: var(--color-primary); line-height: 1.8;"><?= \App\Core\Security::escape($caseStudy['objective']) ?></p>
    </div>

    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <div class="card">
            <h2 style="font-size: 1.2rem; color: var(--color-danger); margin-bottom: 0.75rem;">تعریف مسئله (Problem)</h2>
            <div><?= nl2br(\App\Core\Security::escape($caseStudy['problem'])) ?></div>
        </div>
        <div class="card">
            <h2 style="font-size: 1.2rem; color: var(--color-primary); margin-bottom: 0.75rem;">استراتژی اجرایی (Strategy)</h2>
            <div><?= nl2br(\App\Core\Security::escape($caseStudy['strategy'])) ?></div>
        </div>
        <div class="card">
            <h2 style="font-size: 1.2rem; color: var(--color-accent); margin-bottom: 0.75rem;">معماری و توسعه (Architecture)</h2>
            <div><?= nl2br(\App\Core\Security::escape($caseStudy['architecture'])) ?></div>
        </div>
        <div class="card" style="background: rgba(16, 185, 129, 0.05); border-color: var(--color-success);">
            <h2 style="font-size: 1.2rem; color: var(--color-success); margin-bottom: 0.75rem;">نتایج حاصله (Results)</h2>
            <div><?= nl2br(\App\Core\Security::escape($caseStudy['results'])) ?></div>
        </div>
    </div>
</div>
