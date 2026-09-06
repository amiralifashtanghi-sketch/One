<header style="background:var(--eafd-color-surface); border-bottom:1px solid var(--eafd-color-border); padding:15px 0; position:sticky; top:0; z-index:1000; backdrop-filter:blur(10px);">
    <div class="container" style="display:flex; justify-content:space-between; align-items:center;">
        <div style="display:flex; align-items:center; gap:30px;">
            <a href="/" style="font-size:1.5rem; font-weight:bold; color:var(--eafd-color-primary); text-decoration:none;">
                EAFD <span style="font-size:0.8rem; color:var(--eafd-color-secondary);">Platform</span>
            </a>
            <?php \App\Core\View::partial('partials/nav'); ?>
        </div>

        <div style="display:flex; align-items:center; gap:15px;">
            <a href="/cart" class="btn btn-secondary" style="padding:8px 16px; font-size:0.9rem;">
                🛒 سبد خرید (<?= \App\Core\Cart::count() ?>)
            </a>

            <?php if (\App\Core\Auth::check()): ?>
                <a href="/account" class="btn btn-primary" style="padding:8px 16px; font-size:0.9rem;">
                    👤 حساب کاربری
                </a>
            <?php else: ?>
                <a href="/login" class="btn btn-primary" style="padding:8px 16px; font-size:0.9rem;">
                    ورود / ثبت‌نام
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>
