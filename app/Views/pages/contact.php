<div class="container" style="padding: 60px 20px;">
    <div style="max-width: 800px; margin: 0 auto;">
        <h1 style="font-size: 2.2rem; margin-bottom: 16px; color: var(--eafd-color-text-emphasis);">تماس با ما</h1>
        <p style="font-size: 1.05rem; color: var(--eafd-color-text-muted); margin-bottom: 40px;">
            جهت ارتباط با تیم توسعه و پشتیبانی EAFD می‌توانید از فرم زیر یا اطلاعات تماس مستقیم استفاده نمایید.
        </p>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            <div class="card">
                <h2 style="font-size: 1.3rem; margin-bottom: 20px; color: var(--eafd-color-primary);">ارسال پیام مستقیم</h2>
                <form action="#" method="post" style="display: flex; flex-direction: column; gap: 16px;">
                    <div>
                        <label for="name" style="display: block; margin-bottom: 6px; font-weight: 500;">نام و نام خانوادگی</label>
                        <input type="text" id="name" name="name" class="input" style="width: 100%;" required>
                    </div>
                    <div>
                        <label for="email" style="display: block; margin-bottom: 6px; font-weight: 500;">ایمیل / شماره همراه</label>
                        <input type="text" id="email" name="contact_info" class="input" style="width: 100%;" required>
                    </div>
                    <div>
                        <label for="message" style="display: block; margin-bottom: 6px; font-weight: 500;">متن پیام</label>
                        <textarea id="message" name="message" class="input" rows="5" style="width: 100%; resize: vertical;" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="margin-top: 10px;">ارسال پیام</button>
                </form>
            </div>

            <div style="display: flex; flex-direction: column; gap: 20px;">
                <div class="card">
                    <h3 style="font-size: 1.1rem; margin-bottom: 8px;">ایمیل پشتیبانی</h3>
                    <p style="color: var(--eafd-color-text-muted); margin: 0;">info@eafd.ir</p>
                </div>
                <div class="card">
                    <h3 style="font-size: 1.1rem; margin-bottom: 8px;">ساعات پاسخگویی</h3>
                    <p style="color: var(--eafd-color-text-muted); margin: 0;">شنبه تا چهارشنبه - ۹ الی ۱۷</p>
                </div>
            </div>
        </div>
    </div>
</div>
