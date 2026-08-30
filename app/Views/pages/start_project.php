<div class="container" style="max-width: 750px; padding: 2rem 0;">
    <div style="text-align: center; margin-bottom: 2.5rem;">
        <h1 style="font-size: 2.25rem; color: var(--color-text);">فرم پیکربندی و شروع پروژه EAFD</h1>
        <p style="color: var(--color-text-muted); margin-top: 0.5rem;">با انتخاب گزینه‌های زیر، برآورد دقیق فنی و زمان‌بندی اجرای سیستم را دریافت کنید.</p>
    </div>

    <div class="card">
        <form id="projectForm" style="display: flex; flex-direction: column; gap: 1.25rem;">
            <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::escape($csrfToken) ?>">

            <div class="grid grid-cols-2" style="gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">نام و نام خانوادگی:</label>
                    <input type="text" name="name" required placeholder="علی حسینی" style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">شماره تماس ارتباطی:</label>
                    <input type="tel" name="phone" required placeholder="۰۹۱۲..." style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
                </div>
            </div>

            <div class="grid grid-cols-2" style="gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">پست الکترونیک (اختیاری):</label>
                    <input type="email" name="email" placeholder="info@company.com" style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">نوع خدمت مد نظر:</label>
                    <select name="project_type" required style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
                        <option value="طراحی سایت اختصاصی">طراحی سایت اختصاصی (Custom Web)</option>
                        <option value="طراحی سایت وردپرسی">طراحی سایت وردپرسی اختصاصی</option>
                        <option value="سئو و بهینه‌سازی">سئو و بهینه‌سازی موتورهای جستجو</option>
                        <option value="توسعه قالب اختصاصی">توسعه قالب اختصاصی وردپرس</option>
                        <option value="دیجیتال مارکتینگ">دیجیتال مارکتینگ و فروش آنلاین</option>
                    </select>
                </div>
            </div>

            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">بودجه تقریبی در نظر گرفته‌شده:</label>
                <select name="budget" style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
                    <option value="زیر ۵۰ میلیون تومان">زیر ۵۰ میلیون تومان</option>
                    <option value="۵۰ تا ۱۰۰ میلیون تومان">۵۰ تا ۱۰۰ میلیون تومان</option>
                    <option value="۱۰۰ تا ۲۰۰ میلیون تومان">۱۰۰ تا ۲۰۰ میلیون تومان</option>
                    <option value="بالای ۲۰۰ میلیون تومان">بالای ۲۰۰ میلیون تومان</option>
                </select>
            </div>

            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">توضیحات و نیازمندی‌های کلیدی پروژه:</label>
                <textarea name="message" rows="5" required placeholder="شرح اهداف پروژه، امکانات مورد انتظار..." style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text); font-family: inherit;"></textarea>
            </div>

            <button type="submit" id="submitProjectBtn" class="btn btn-primary" style="padding: 0.875rem 2rem; width: 100%; justify-content: center;">ثبت نهایی و ارسال پروژه به EAFD ←</button>
        </form>

        <div id="projectSuccessMsg" style="display: none; background: rgba(16, 185, 129, 0.1); border: 1px solid var(--color-success); border-radius: var(--radius-md); padding: 1.25rem; color: var(--color-success); font-size: 0.95rem; text-align: center; margin-top: 1rem;">
            درخواست پروژه شما با موفقیت ثبت شد. کارشناسان EAFD به‌زودی با شما تماس خواهند گرفت.
        </div>
    </div>
</div>

<script>
document.getElementById('projectForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('submitProjectBtn');
    const msg = document.getElementById('projectSuccessMsg');
    btn.disabled = true;
    btn.innerText = 'در حال ثبت درخواست...';

    const baseUrl = '<?= \App\Core\Security::getBaseUrl() ?>';
    const formData = new FormData(this);
    try {
        const res = await fetch(baseUrl + '/api/start-project', { method: 'POST', body: formData });
        const data = await res.json();

        if (res.ok && data.success) {
            this.style.display = 'none';
            msg.style.display = 'block';
        } else {
            alert(data.error || 'خطا در ثبت درخواست');
            btn.disabled = false;
            btn.innerText = 'ثبت نهایی و ارسال پروژه به EAFD ←';
        }
    } catch (err) {
        alert('خطا در برقراری ارتباط با سرور.');
        btn.disabled = false;
        btn.innerText = 'ثبت نهایی و ارسال پروژه به EAFD ←';
    }
});
</script>
