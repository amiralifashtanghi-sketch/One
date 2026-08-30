<div class="container" style="max-width: 800px; padding: 2rem 0;">
    <div style="text-align: center; margin-bottom: 2.5rem;">
        <h1 style="font-size: 2.25rem; color: var(--color-text);">تحلیل هوشمند آنلاین سلامت و سئوی وب‌سایت</h1>
        <p style="color: var(--color-text-muted); margin-top: 0.5rem;">آدرس وب‌سایت خود را وارد کنید تا پارامترهای واقعی سرعت، امنیت SSL و هدرهای سئو سنجیده شوند.</p>
    </div>

    <!-- Audit Input Form -->
    <div class="card" style="margin-bottom: 2rem;">
        <form id="auditForm" style="display: flex; gap: 1rem;">
            <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::escape($csrfToken) ?>">
            <input type="url" name="url" placeholder="https://yourwebsite.com" required style="flex: 1; padding: 0.875rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text); font-family: monospace;">
            <button type="submit" id="submitBtn" class="btn btn-primary" style="padding: 0.875rem 1.75rem;">شروع آنالیز فوری ←</button>
        </form>
    </div>

    <!-- Results Area -->
    <div id="auditResult" style="display: none;">
        <div class="card" style="background: var(--color-surface); border: 1px solid var(--color-surface-border); padding: 2rem;">
            <h2 style="font-size: 1.25rem; color: var(--color-text); margin-bottom: 1.5rem; text-align: center;">گزارش ارزیابی فنی EAFD</h2>

            <div class="grid grid-cols-3" style="margin-bottom: 2rem; text-align: center;">
                <div style="padding: 1rem; background: var(--color-bg); border-radius: var(--radius-md);">
                    <div style="font-size: 0.8rem; color: var(--color-text-muted);">شاخص سرعت (Performance)</div>
                    <div id="perfScore" style="font-size: 2.25rem; font-weight: bold; color: var(--color-primary); margin-top: 0.25rem;">0</div>
                </div>
                <div style="padding: 1rem; background: var(--color-bg); border-radius: var(--radius-md);">
                    <div style="font-size: 0.8rem; color: var(--color-text-muted);">هدرهای امنیتی (Security)</div>
                    <div id="secScore" style="font-size: 2.25rem; font-weight: bold; color: var(--color-success); margin-top: 0.25rem;">0</div>
                </div>
                <div style="padding: 1rem; background: var(--color-bg); border-radius: var(--radius-md);">
                    <div style="font-size: 0.8rem; color: var(--color-text-muted);">ساختار سئو (SEO Tags)</div>
                    <div id="seoScore" style="font-size: 2.25rem; font-weight: bold; color: var(--color-accent); margin-top: 0.25rem;">0</div>
                </div>
            </div>

            <ul id="auditDetails" style="list-style: none; display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.9rem;">
                <!-- Filled via JS -->
            </ul>
        </div>
    </div>
</div>

<script>
document.getElementById('auditForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');
    const resultDiv = document.getElementById('auditResult');
    btn.disabled = true;
    btn.innerText = 'در حال تحلیل...';

    const formData = new FormData(this);
    try {
        const res = await fetch('/api/audit', { method: 'POST', body: formData });
        const data = await res.json();

        if (res.ok) {
            document.getElementById('perfScore').innerText = data.scores.performance;
            document.getElementById('secScore').innerText = data.scores.security;
            document.getElementById('seoScore').innerText = data.scores.seo;

            const details = document.getElementById('auditDetails');
            details.innerHTML = `
                <li style="display:flex; justify-content:space-between; padding:0.5rem 0; border-bottom:1px solid var(--color-surface-border);"><span>پاسخ سرور (Response Time):</span><strong style="color:var(--color-primary);">${data.response_time_ms} ms</strong></li>
                <li style="display:flex; justify-content:space-between; padding:0.5rem 0; border-bottom:1px solid var(--color-surface-border);"><span>پروتوکل امن SSL (HTTPS):</span><strong style="color:${data.details.has_ssl ? 'var(--color-success)':'var(--color-danger)'};">${data.details.has_ssl ? 'فعال ✓':'غیرفعال ✗'}</strong></li>
                <li style="display:flex; justify-content:space-between; padding:0.5rem 0; border-bottom:1px solid var(--color-surface-border);"><span>تگ عنوان صفحه (Title):</span><strong>${data.details.page_title}</strong></li>
                <li style="display:flex; justify-content:space-between; padding:0.5rem 0;"><span>تگ توضیحات سئو (Meta Description):</span><strong style="color:${data.details.has_meta_desc ? 'var(--color-success)':'var(--color-danger)'};">${data.details.has_meta_desc ? 'موجود ✓':'یافت نشد ✗'}</strong></li>
            `;
            resultDiv.style.display = 'block';
        } else {
            alert(data.error || 'خطا در آنالیز سایت');
        }
    } catch (err) {
        alert('خطا در برقراری ارتباط با سرور.');
    } finally {
        btn.disabled = false;
        btn.innerText = 'شروع آنالیز فوری ←';
    }
});
</script>
