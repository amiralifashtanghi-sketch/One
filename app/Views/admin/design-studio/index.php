<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
    <div>
        <h1 style="margin:0;">استودیو طراحی اختصاصی (Design Studio)</h1>
        <p style="margin:5px 0 0 0; color:var(--eafd-color-text-muted);">مدیریت یکپارچه پالت رنگ، تایپوگرافی، فواصل و پیش‌نمایش زنده دیزاین سیستم EAFD</p>
    </div>
    <div>
        <span id="contrast-badge" class="badge <?= $wcagPass ? 'badge-success' : 'badge-danger' ?>">
            <?= $wcagPass ? '✓ تایید WCAG 2.2 AA' : '✕ کنتراست غیرمجاز' ?> (<?= $contrastRatio ?>:1)
        </span>
    </div>
</div>

<div class="grid grid-cols-2" style="gap: 30px;">
    <!-- Controls Panel -->
    <div class="card">
        <h2 style="font-size:1.3rem; margin-bottom:20px; border-bottom:1px solid var(--eafd-color-border); padding-bottom:10px;">پیکربندی Design Tokens</h2>

        <form method="POST" action="/admin/design-studio/update">
            <input type="hidden" name="_csrf_token" value="<?= \App\Core\Csrf::generate() ?>">

            <!-- Color Palette Section -->
            <h3 style="font-size:1.1rem; color:var(--eafd-color-secondary); margin-top:15px;">پالت رنگ سازمانی</h3>

            <div class="form-group" style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">رنگ اصلی (Primary):</label>
                <div style="display:flex; gap:10px;">
                    <input type="color" class="token-color-input" data-token="--eafd-color-primary" value="<?= htmlspecialchars($tokens['--eafd-color-primary'] ?? '#0B63D8') ?>" style="width:50px; height:40px; border:none; cursor:pointer;">
                    <input type="text" class="token-text-input form-control" data-token="--eafd-color-primary" name="tokens[--eafd-color-primary]" value="<?= htmlspecialchars($tokens['--eafd-color-primary'] ?? '#0B63D8') ?>">
                </div>
            </div>

            <div class="form-group" style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">رنگ ثانویه (Secondary):</label>
                <div style="display:flex; gap:10px;">
                    <input type="color" class="token-color-input" data-token="--eafd-color-secondary" value="<?= htmlspecialchars($tokens['--eafd-color-secondary'] ?? '#18D6D8') ?>" style="width:50px; height:40px; border:none; cursor:pointer;">
                    <input type="text" class="token-text-input form-control" data-token="--eafd-color-secondary" name="tokens[--eafd-color-secondary]" value="<?= htmlspecialchars($tokens['--eafd-color-secondary'] ?? '#18D6D8') ?>">
                </div>
            </div>

            <div class="form-group" style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">رنگ پس‌زمینه (Background):</label>
                <div style="display:flex; gap:10px;">
                    <input type="color" class="token-color-input" data-token="--eafd-color-bg" value="<?= htmlspecialchars($tokens['--eafd-color-bg'] ?? '#090D16') ?>" style="width:50px; height:40px; border:none; cursor:pointer;">
                    <input type="text" class="token-text-input form-control" data-token="--eafd-color-bg" name="tokens[--eafd-color-bg]" value="<?= htmlspecialchars($tokens['--eafd-color-bg'] ?? '#090D16') ?>">
                </div>
            </div>

            <div class="form-group" style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">رنگ متن اصلی (Text):</label>
                <div style="display:flex; gap:10px;">
                    <input type="color" class="token-color-input" data-token="--eafd-color-text" value="<?= htmlspecialchars($tokens['--eafd-color-text'] ?? '#F1F5F9') ?>" style="width:50px; height:40px; border:none; cursor:pointer;">
                    <input type="text" class="token-text-input form-control" data-token="--eafd-color-text" name="tokens[--eafd-color-text]" value="<?= htmlspecialchars($tokens['--eafd-color-text'] ?? '#F1F5F9') ?>">
                </div>
            </div>

            <!-- Layout & Radius Section -->
            <h3 style="font-size:1.1rem; color:var(--eafd-color-secondary); margin-top:25px;">انحنای گوشه‌ها (Border Radius)</h3>
            <div class="form-group" style="margin-bottom:20px;">
                <input type="text" class="form-control" name="tokens[--eafd-border-radius]" value="<?= htmlspecialchars($tokens['--eafd-border-radius'] ?? '12px') ?>">
            </div>

            <div style="display:flex; gap:15px; margin-top:30px;">
                <button type="submit" class="btn btn-primary" style="flex:1;">ذخیره و انتشار CSS Tokens</button>
            </div>
        </form>

        <form method="POST" action="/admin/design-studio/reset" style="margin-top:15px;">
            <input type="hidden" name="_csrf_token" value="<?= \App\Core\Csrf::generate() ?>">
            <button type="submit" class="btn btn-secondary" style="width:100%; color:#fca5a5;" onclick="return confirm('آیا از بازگردانی تمامی تنظیمات به حالت اولیه اطمینان دارید؟');">بازگردانی به تنظیمات پیش‌فرض</button>
        </form>
    </div>

    <!-- Live Sandbox Preview Panel -->
    <div class="card" style="position:sticky; top:20px; align-self:start;">
        <h2 style="font-size:1.3rem; margin-bottom:20px; border-bottom:1px solid var(--eafd-color-border); padding-bottom:10px;">پیش‌نمایش زنده (Live Sandbox)</h2>

        <div class="sandbox-preview-box" style="padding:25px; border-radius:var(--eafd-border-radius); background:var(--eafd-color-bg); border:1px solid var(--eafd-color-border);">
            <span class="badge badge-success" style="margin-bottom:15px; display:inline-block;">نمونه کارت کامپوننت</span>
            <h2 style="color:var(--eafd-color-text); margin-bottom:10px;">عنوان نمونه H2 با دیزاین سیستم EAFD</h2>
            <p style="color:var(--eafd-color-text-muted); font-size:0.95rem; margin-bottom:20px;">
                این یک متن نمونه جهت تست تغییرات همزمان رنگ، کنتراست، انحناها و فونت لوکال وزیرمتن در استودیو طراحی می‌باشد.
            </p>
            <div style="display:flex; gap:10px;">
                <button class="btn btn-primary" type="button">دکمه اصلی (Primary)</button>
                <button class="btn btn-secondary" type="button">دکمه ثانویه (Secondary)</button>
            </div>
        </div>
    </div>
</div>

<script src="/assets/js/design-studio.js"></script>
