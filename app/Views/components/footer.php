<footer class="eafd-footer">
    <div class="container">
        <div class="grid grid-cols-4" style="margin-bottom:var(--eafd-spacing-xl);">
            <div>
                <a href="/" class="eafd-logo" style="margin-bottom:var(--eafd-spacing-md);">
                    <span class="eafd-logo-text">EAFD</span>
                </a>
                <p style="font-size:0.9rem; line-height:1.7; color:var(--eafd-color-text-muted);">
                    معماری و مهندسی سیستم‌های وب پیشرفته بر پایه طراحی سیستماتیک، ۱۰۰٪ فارسی و بدون وابستگی‌های خارجی.
                </p>
            </div>

            <div>
                <h4 style="font-size:1rem; font-weight:700; color:var(--eafd-color-text); margin-bottom:var(--eafd-spacing-md);">دسترسی سریع</h4>
                <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:10px; font-size:0.9rem;">
                    <li><a href="/" class="eafd-nav-link">صفحه اصلی</a></li>
                    <li><a href="/services" class="eafd-nav-link">خدمات</a></li>
                    <li><a href="/projects" class="eafd-nav-link">پروژه‌ها</a></li>
                    <li><a href="/store" class="eafd-nav-link">محصولات</a></li>
                </ul>
            </div>

            <div>
                <h4 style="font-size:1rem; font-weight:700; color:var(--eafd-color-text); margin-bottom:var(--eafd-spacing-md);">آزمایشگاه EAFD</h4>
                <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:10px; font-size:0.9rem;">
                    <li><a href="/lab" class="eafd-nav-link">آزمایشگاه و ابزارها</a></li>
                    <li><a href="/lab/tool/wcag-contrast-checker" class="eafd-nav-link">تست کنتراست WCAG</a></li>
                    <li><a href="/lab/quiz/lighthouse-readiness-quiz" class="eafd-nav-link">آزمون Lighthouse</a></li>
                </ul>
            </div>

            <div>
                <h4 style="font-size:1rem; font-weight:700; color:var(--eafd-color-text); margin-bottom:var(--eafd-spacing-md);">ارتباط مستقیم</h4>
                <p style="font-size:0.9rem; margin-bottom:8px; color:var(--eafd-color-text-muted);">تلفن: ۰۹۱۵۰۵۹۱۷۱۰</p>
                <p style="font-size:0.9rem; margin-bottom:12px; color:var(--eafd-color-text-muted);">ایمیل: admin@eafd.ir</p>
                <p style="font-size:0.85rem; color:var(--eafd-color-text-muted);">طراحی و ساخته شده به‌دست eafd.ir</p>
            </div>
        </div>

        <div style="text-align:center; border-top:1px solid var(--eafd-color-border); padding-top:var(--eafd-spacing-lg); font-size:0.85rem; color:var(--eafd-color-text-muted);">
            © <?= date('Y') ?> تمامی حقوق این پلتفرم متعلق به سامانه EAFD می‌باشد.
        </div>
    </div>
</footer>

<style>
.eafd-footer {
    background: rgba(8, 8, 8, 0.95);
    border-top: 1px solid var(--eafd-color-border);
    padding: var(--eafd-spacing-xxl) 0 var(--eafd-spacing-lg) 0;
    margin-top: auto;
}
</style>
