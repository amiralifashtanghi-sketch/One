<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
    <div>
        <h1 style="margin:0; font-size:1.8rem; color:var(--eafd-color-text);">استودیو طراحی و مدیریت دیزاین سیستم EAFD</h1>
        <p style="margin:6px 0 0 0; color:var(--eafd-color-text-muted); font-size:0.95rem;">تنظیم زنده متغیرهای CSS (Design Tokens)، لایو سندباکس و سنجش هوشمند کنتراست WCAG 2.2 AA</p>
    </div>
    <div>
        <span id="contrast-badge" class="badge" style="padding:8px 16px; border-radius:6px; font-weight:700; font-size:0.9rem; background:<?= $wcagPass ? '#14532d' : '#7f1d1d' ?>; color:<?= $wcagPass ? '#86efac' : '#fca5a5' ?>;">
            <?= $wcagPass ? '✓ تایید WCAG 2.2 AA' : '✕ کنتراست ضعیف' ?> (<?= $contrastRatio ?>:1)
        </span>
    </div>
</div>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap: 30px; align-items: start;">
    <!-- Controls Panel -->
    <div style="display:flex; flex-direction:column; gap:24px;">
        <form method="POST" action="/admin/design-studio/update">
            <input type="hidden" name="_csrf_token" value="<?= \App\Core\Csrf::generate() ?>">

            <!-- Color Tokens Card -->
            <div class="card" style="margin-bottom:24px;">
                <h2 style="font-size:1.2rem; margin-bottom:16px; color:var(--eafd-color-text); border-bottom:1px solid var(--eafd-color-border); padding-bottom:10px;">پالت رنگ اصلی (Editorial Dark)</h2>

                <div style="display:flex; flex-direction:column; gap:16px;">
                    <div>
                        <label style="display:block; margin-bottom:6px; font-weight:600; font-size:0.9rem;">Primary CTA (دکمه و اکشن‌های اصلی):</label>
                        <div style="display:flex; gap:10px; align-items:center;">
                            <input type="color" data-token="--eafd-color-primary" value="<?= htmlspecialchars($tokens['--eafd-color-primary'] ?? '#FFFFFF') ?>" style="width:48px; height:38px; border:1px solid var(--eafd-color-border); border-radius:6px; cursor:pointer; background:none;">
                            <input type="text" dir="ltr" class="input" data-token="--eafd-color-primary" name="tokens[--eafd-color-primary]" value="<?= htmlspecialchars($tokens['--eafd-color-primary'] ?? '#FFFFFF') ?>" style="flex:1; direction:ltr; text-align:left;">
                        </div>
                    </div>

                    <div>
                        <label style="display:block; margin-bottom:6px; font-weight:600; font-size:0.9rem;">پس‌زمینه اصلی (Background):</label>
                        <div style="display:flex; gap:10px; align-items:center;">
                            <input type="color" data-token="--eafd-color-bg" value="<?= htmlspecialchars($tokens['--eafd-color-bg'] ?? '#080808') ?>" style="width:48px; height:38px; border:1px solid var(--eafd-color-border); border-radius:6px; cursor:pointer; background:none;">
                            <input type="text" dir="ltr" class="input" data-token="--eafd-color-bg" name="tokens[--eafd-color-bg]" value="<?= htmlspecialchars($tokens['--eafd-color-bg'] ?? '#080808') ?>" style="flex:1; direction:ltr; text-align:left;">
                        </div>
                    </div>

                    <div>
                        <label style="display:block; margin-bottom:6px; font-weight:600; font-size:0.9rem;">متن اصلی (Primary Text - H1/H2):</label>
                        <div style="display:flex; gap:10px; align-items:center;">
                            <input type="color" data-token="--eafd-color-text" value="<?= htmlspecialchars($tokens['--eafd-color-text'] ?? '#F5F5F5') ?>" style="width:48px; height:38px; border:1px solid var(--eafd-color-border); border-radius:6px; cursor:pointer; background:none;">
                            <input type="text" dir="ltr" class="input" data-token="--eafd-color-text" name="tokens[--eafd-color-text]" value="<?= htmlspecialchars($tokens['--eafd-color-text'] ?? '#F5F5F5') ?>" style="flex:1; direction:ltr; text-align:left;">
                        </div>
                    </div>

                    <div>
                        <label style="display:block; margin-bottom:6px; font-weight:600; font-size:0.9rem;">متن فرعی (Secondary Text - Captions):</label>
                        <div style="display:flex; gap:10px; align-items:center;">
                            <input type="color" data-token="--eafd-color-text-muted" value="<?= htmlspecialchars($tokens['--eafd-color-text-muted'] ?? '#8A8A92') ?>" style="width:48px; height:38px; border:1px solid var(--eafd-color-border); border-radius:6px; cursor:pointer; background:none;">
                            <input type="text" dir="ltr" class="input" data-token="--eafd-color-text-muted" name="tokens[--eafd-color-text-muted]" value="<?= htmlspecialchars($tokens['--eafd-color-text-muted'] ?? '#8A8A92') ?>" style="flex:1; direction:ltr; text-align:left;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Layout, Grid & Surface Card -->
            <div class="card" style="margin-bottom:24px;">
                <h2 style="font-size:1.2rem; margin-bottom:16px; color:var(--eafd-color-text); border-bottom:1px solid var(--eafd-color-border); padding-bottom:10px;">شبکه‌بندی، انحنا و کارت‌ها (Grid & Surface)</h2>

                <div style="display:flex; flex-direction:column; gap:18px;">
                    <div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                            <label style="font-weight:600; font-size:0.9rem;">اندازه خانه‌های شبکه (Grid Size):</label>
                            <span data-token="--eafd-grid-size" style="font-weight:700; color:var(--eafd-color-primary);"><?= htmlspecialchars($tokens['--eafd-grid-size'] ?? '32px') ?></span>
                        </div>
                        <input type="range" min="16" max="64" step="2" data-token="--eafd-grid-size" value="<?= (int)($tokens['--eafd-grid-size'] ?? '32') ?>" style="width:100%;">
                        <input type="hidden" name="tokens[--eafd-grid-size]" data-token="--eafd-grid-size" value="<?= htmlspecialchars($tokens['--eafd-grid-size'] ?? '32px') ?>">
                    </div>

                    <div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                            <label style="font-weight:600; font-size:0.9rem;">شعاع انحنای کارت‌ها و دکمه‌ها (Border Radius):</label>
                            <span data-token="--eafd-border-radius" style="font-weight:700; color:var(--eafd-color-primary);"><?= htmlspecialchars($tokens['--eafd-border-radius'] ?? '12px') ?></span>
                        </div>
                        <input type="range" min="0" max="24" step="2" data-token="--eafd-border-radius" value="<?= (int)($tokens['--eafd-border-radius'] ?? '12') ?>" style="width:100%;">
                        <input type="hidden" name="tokens[--eafd-border-radius]" data-token="--eafd-border-radius" value="<?= htmlspecialchars($tokens['--eafd-border-radius'] ?? '12px') ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:14px;">
                ذخیره و انتشار تغییرات دیزاین سیستم
            </button>
        </form>

        <form method="POST" action="/admin/design-studio/reset">
            <input type="hidden" name="_csrf_token" value="<?= \App\Core\Csrf::generate() ?>">
            <button type="submit" class="btn btn-secondary" style="width:100%; justify-content:center; color:#fca5a5; padding:12px;" onclick="return confirm('آیا از بازگردانی تمامی تنظیمات به حالت اولیه اطمینان دارید؟');">
                بازگردانی به تنظیمات پیش‌فرض
            </button>
        </form>
    </div>

    <!-- Live Preview Panel -->
    <div style="position:sticky; top:20px;">
        <div class="card eafd-grid-subtle" style="padding:30px; border:1px solid var(--eafd-color-border); border-radius:var(--eafd-border-radius); background-color:var(--eafd-color-bg);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; border-bottom:1px solid var(--eafd-color-border); padding-bottom:12px;">
                <span style="font-size:0.85rem; font-weight:700; color:var(--eafd-color-text-muted); text-transform:uppercase; letter-spacing:0.05em;">پیش‌نمایش زنده دیزاین سیستم</span>
                <span style="font-size:0.75rem; background:rgba(255,255,255,0.06); padding:3px 8px; border-radius:4px; color:var(--eafd-color-text-muted);">Live Sandbox</span>
            </div>

            <h1 style="font-size:1.5rem; margin-bottom:12px; color:var(--eafd-color-text); word-break:break-word;">عنوان نمونه وب‌سایت (نمونه H1)</h1>
            <p style="color:var(--eafd-color-text-muted); line-height:1.7; margin-bottom:24px; font-size:0.95rem;">
                این یک متن نمونه برای بررسی همزمان رنگ متن اصلی، متن فرعی، انحنای کارت‌ها و اندازه‌گیری پس‌زمینه شبکه‌ای می‌باشد.
            </p>

            <div style="display:flex; gap:12px; flex-wrap:wrap; margin-bottom:30px;">
                <button type="button" class="btn btn-primary">
                    دکمه اکشن اصلی (Primary CTA)
                    <?php \App\Core\View::partial('components/icons/arrow'); ?>
                </button>
                <button type="button" class="btn btn-secondary">دکمه ثانویه</button>
            </div>

            <div class="card" style="background:var(--eafd-color-surface); border:1px solid var(--eafd-color-border); border-radius:var(--eafd-border-radius); padding:20px;">
                <h3 style="font-size:1.1rem; margin-bottom:8px; color:var(--eafd-color-text);">کارت نمونه کامپوننت</h3>
                <p style="margin:0; font-size:0.9rem; color:var(--eafd-color-text-muted);">ظاهر کارت کاملاً وابسته به متغیرهای `--eafd-color-surface` و `--eafd-border-radius` می‌باشد.</p>
            </div>
        </div>
    </div>
</div>

<script src="/assets/js/design-studio.js"></script>
