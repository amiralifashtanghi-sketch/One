<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
    <div>
        <h1 style="margin:0;">استودیو طراحی اختصاصی (Design Studio)</h1>
        <p style="margin:5px 0 0 0; color:var(--eafd-color-text-muted);">مدیریت یکپارچه پالت رنگ، تایپوگرافی، فواصل، خطوط شبکه‌ای و پیش‌نمایش زنده دیزاین سیستم EAFD</p>
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
            <h3 style="font-size:1.1rem; color:var(--eafd-color-text); margin-top:15px;">پالت رنگ سازمانی (Editorial Dark)</h3>

            <div class="form-group" style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">رنگ دکمه اصلی (Primary CTA):</label>
                <div style="display:flex; gap:10px;">
                    <input type="color" class="token-color-input" data-token="--eafd-color-primary" value="<?= htmlspecialchars($tokens['--eafd-color-primary'] ?? '#FFFFFF') ?>" style="width:50px; height:40px; border:none; cursor:pointer;">
                    <input type="text" class="token-text-input form-control" data-token="--eafd-color-primary" name="tokens[--eafd-color-primary]" value="<?= htmlspecialchars($tokens['--eafd-color-primary'] ?? '#FFFFFF') ?>">
                </div>
            </div>

            <div class="form-group" style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">رنگ پس‌زمینه اصلی (Background):</label>
                <div style="display:flex; gap:10px;">
                    <input type="color" class="token-color-input" data-token="--eafd-color-bg" value="<?= htmlspecialchars($tokens['--eafd-color-bg'] ?? '#080808') ?>" style="width:50px; height:40px; border:none; cursor:pointer;">
                    <input type="text" class="token-text-input form-control" data-token="--eafd-color-bg" name="tokens[--eafd-color-bg]" value="<?= htmlspecialchars($tokens['--eafd-color-bg'] ?? '#080808') ?>">
                </div>
            </div>

            <div class="form-group" style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">رنگ متن اصلی (Primary Text):</label>
                <div style="display:flex; gap:10px;">
                    <input type="color" class="token-color-input" data-token="--eafd-color-text" value="<?= htmlspecialchars($tokens['--eafd-color-text'] ?? '#F5F5F5') ?>" style="width:50px; height:40px; border:none; cursor:pointer;">
                    <input type="text" class="token-text-input form-control" data-token="--eafd-color-text" name="tokens[--eafd-color-text]" value="<?= htmlspecialchars($tokens['--eafd-color-text'] ?? '#F5F5F5') ?>">
                </div>
            </div>

            <div class="form-group" style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">رنگ متن فرعی (Secondary Text):</label>
                <div style="display:flex; gap:10px;">
                    <input type="color" class="token-color-input" data-token="--eafd-color-text-muted" value="<?= htmlspecialchars($tokens['--eafd-color-text-muted'] ?? '#8A8A92') ?>" style="width:50px; height:40px; border:none; cursor:pointer;">
                    <input type="text" class="token-text-input form-control" data-token="--eafd-color-text-muted" name="tokens[--eafd-color-text-muted]" value="<?= htmlspecialchars($tokens['--eafd-color-text-muted'] ?? '#8A8A92') ?>">
                </div>
            </div>

            <!-- Grid & Spacing Section -->
            <h3 style="font-size:1.1rem; color:var(--eafd-color-text); margin-top:25px;">شبکه‌بندی و فواصل (Grid & Spacing)</h3>
            <div class="form-group" style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">اندازه خانه‌های شبکه (Grid Size):</label>
                <input type="text" class="form-control" name="tokens[--eafd-grid-size]" value="<?= htmlspecialchars($tokens['--eafd-grid-size'] ?? '32px') ?>">
            </div>

            <div style="display:flex; gap:15px; margin-top:30px;">
                <button type="submit" class="eafd-btn-primary" style="flex:1; justify-content:center;">ذخیره و انتشار CSS Tokens</button>
            </div>
        </form>

        <form method="POST" action="/admin/design-studio/reset" style="margin-top:15px;">
            <input type="hidden" name="_csrf_token" value="<?= \App\Core\Csrf::generate() ?>">
            <button type="submit" class="eafd-btn-secondary" style="width:100%; color:#fca5a5; justify-content:center;" onclick="return confirm('آیا از بازگردانی تمامی تنظیمات به حالت اولیه اطمینان دارید؟');">بازگردانی به تنظیمات پیش‌فرض</button>
        </form>
    </div>

    <!-- Live Sandbox Preview Panel -->
    <div class="card" style="position:sticky; top:20px; align-self:start;">
        <h2 style="font-size:1.3rem; margin-bottom:20px; border-bottom:1px solid var(--eafd-color-border); padding-bottom:10px;">پیش‌نمایش زنده (Live Sandbox)</h2>

        <div class="sandbox-preview-box" style="padding:25px; border-radius:var(--eafd-border-radius); background:var(--eafd-color-bg); border:1px solid var(--eafd-color-border); background-image: linear-gradient(to right, rgba(255,255,255,0.035) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.035) 1px, transparent 1px); background-size: var(--eafd-grid-size) var(--eafd-grid-size);">
            <h2 style="color:var(--eafd-color-text); margin-bottom:10px; font-weight:800;">وب‌سایت نمی‌سازیم؛ سیستم دیجیتال می‌سازیم.</h2>
            <p style="color:var(--eafd-color-text-muted); font-size:0.95rem; margin-bottom:20px;">
                نمونه رندر پالت تیره پریمیوم، خطوط شبکه‌ای ۳۲ پیکسلی و دکمه‌های کپسولی اکسیستم EAFD.
            </p>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button class="eafd-btn-primary" type="button">
                    شروع یک پروژه
                    <?php \App\Core\View::partial('components/icons/arrow'); ?>
                </button>
                <button class="eafd-btn-secondary" type="button">مشاهده پروژه‌ها</button>
            </div>
        </div>
    </div>
</div>

<script src="/assets/js/design-studio.js"></script>
