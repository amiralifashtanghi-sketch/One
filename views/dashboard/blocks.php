<?php
ob_start();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h2 style="margin: 0;">مدیریت بلوک‌های صفحه بیو (۱۸ نوع)</h2>
    <button onclick="document.getElementById('addModal').style.display='flex'" class="btn btn-success">+ افزودن بلوک جدید</button>
</div>

<!-- List of current blocks -->
<div class="card">
    <?php if (empty($blocks)): ?>
        <p style="text-align: center; color: var(--muted); margin: 2rem 0;">هنوز هیچ بلوکی اضافه نکرده‌اید. روی دکمه «افزودن بلوک جدید» کلیک کنید.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ترتیب</th>
                    <th>عنوان بلوک</th>
                    <th>نوع بلوک</th>
                    <th>وضعیت</th>
                    <th>کلیک‌ها</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($blocks as $idx => $block): ?>
                    <tr>
                        <td><?= $idx + 1 ?></td>
                        <td><strong><?= htmlspecialchars($block['title'] ?: 'بدون عنوان') ?></strong></td>
                        <td><code><?= htmlspecialchars($block['type']) ?></code></td>
                        <td>
                            <?php if ($block['is_active']): ?>
                                <span class="badge badge-success">فعال</span>
                            <?php else: ?>
                                <span class="badge badge-danger">غیرفعال</span>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= number_format($block['clicks_count']) ?></strong></td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <form method="POST" action="/dashboard/blocks/toggle" style="display: inline;">
                                    <input type="hidden" name="block_id" value="<?= $block['id'] ?>">
                                    <button type="submit" class="btn" style="padding: 0.35rem 0.6rem; font-size: 0.8rem;">
                                        <?= $block['is_active'] ? 'غیرفعال‌سازی' : 'فعال‌سازی' ?>
                                    </button>
                                </form>
                                <form method="POST" action="/dashboard/blocks/delete" style="display: inline;" onsubmit="return confirm('آیا از حذف این بلوک اطمینان دارید؟');">
                                    <input type="hidden" name="block_id" value="<?= $block['id'] ?>">
                                    <button type="submit" class="btn btn-danger" style="padding: 0.35rem 0.6rem; font-size: 0.8rem;">حذف 🗑️</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Modal Add Block -->
<div id="addModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); align-items: center; justify-content: center; z-index: 1000; padding: 1rem;">
    <div style="background: var(--card-bg); border-radius: 16px; padding: 1.5rem; width: 100%; max-width: 550px; max-height: 90vh; overflow-y: auto; border: 1px solid var(--border);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="margin:0;">افزودن بلوک جدید</h3>
            <button type="button" onclick="document.getElementById('addModal').style.display='none'" style="background: none; border: none; color: #fff; font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>

        <form method="POST" action="/dashboard/blocks/add">
            <div class="form-group">
                <label>نوع بلوک را انتخاب کنید:</label>
                <select name="type" id="blockTypeSelect" onchange="changeBlockFields(this.value)">
                    <option value="link">۱. لینک ساده</option>
                    <option value="image_link">۲. لینک تصویری</option>
                    <option value="website">۳. وب‌سایت (کوتاه‌کننده و کلیک‌شمار)</option>
                    <option value="text">۴. متن و عنوان</option>
                    <option value="social">۵. شبکه‌های اجتماعی و پیام‌رسان‌ها</option>
                    <option value="image">۶. تصویر و پوستر</option>
                    <option value="video">۷. پلیر ویدیو (آپارات / یوتیوب / MP4)</option>
                    <option value="divider">۸. جداکننده</option>
                    <option value="contact">۹. تماس، پیامک و ایمیل</option>
                    <option value="location">۱۰. مسیریابی (نشان، بلد، گوگل مپ)</option>
                    <option value="payment">۱۱. اطلاعات پرداخت (کارت، شبا)</option>
                    <option value="iran_platforms">۱۲. پلتفرم‌های فروش ایران (ترب، باسلام، دیجی‌کالا)</option>
                    <option value="products">۱۳. لیست محصولات (تا ۵ محصول با قیمت و دکمه)</option>
                    <option value="lead_form">۱۴. فرم دریافت اطلاعات مخاطب (لید)</option>
                    <option value="booking">۱۵. رزرو وقت و نوبت‌دهی</option>
                    <option value="faq">۱۶. سوالات متداول کشویی (FAQ)</option>
                    <option value="gallery">۱۷. گالری نمونه‌کارها</option>
                    <option value="countdown">۱۸. شمارش معکوس کمپین</option>
                </select>
            </div>

            <div class="form-group">
                <label>عنوان اصلی بلوک:</label>
                <input type="text" name="title" placeholder="عنوان نمایشی بلوک" required>
            </div>

            <!-- Dynamic Fields per Type -->
            <div id="dynamicFields">
                <!-- Default fields for link -->
                <div class="form-group">
                    <label>آدرس لینک (URL):</label>
                    <input type="text" name="content[url]" placeholder="https://..." required>
                </div>
            </div>

            <button type="submit" class="btn btn-success" style="width: 100%; margin-top: 1rem;">ثبت و افزودن بلوک</button>
        </form>
    </div>
</div>

<script>
    function changeBlockFields(type) {
        const df = document.getElementById('dynamicFields');
        let html = '';

        if (type === 'link') {
            html = `<div class="form-group"><label>آدرس لینک (URL):</label><input type="text" name="content[url]" placeholder="https://..." required></div>`;
        } else if (type === 'image_link') {
            html = `<div class="form-group"><label>آدرس لینک:</label><input type="text" name="content[url]" required></div>
                    <div class="form-group"><label>آدرس تصویر شاخص:</label><input type="text" name="content[image_url]" required></div>`;
        } else if (type === 'website') {
            html = `<div class="form-group"><label>آدرس سایت:</label><input type="text" name="content[url]" required></div>`;
        } else if (type === 'text') {
            html = `<div class="form-group"><label>متن کامل / زیرعنوان:</label><textarea name="content[text]" rows="3" required></textarea></div>`;
        } else if (type === 'social') {
            html = `<div class="form-group"><label>اینستاگرام (آیدی یا لینک):</label><input type="text" name="content[instagram]"></div>
                    <div class="form-group"><label>تلگرام (آیدی یا لینک):</label><input type="text" name="content[telegram]"></div>
                    <div class="form-group"><label>واتساپ (شماره با کد ۹۸):</label><input type="text" name="content[whatsapp]"></div>
                    <div class="form-group"><label>ایتا / روبیکا / بله:</label><input type="text" name="content[eitaa]"></div>`;
        } else if (type === 'image') {
            html = `<div class="form-group"><label>آدرس تصویر:</label><input type="text" name="content[image_url]" required></div>`;
        } else if (type === 'video') {
            html = `<div class="form-group"><label>لینک ویدیو (کد آپارات، لینک یوتیوب یا فایل MP4):</label><input type="text" name="content[video_url]" required></div>`;
        } else if (type === 'divider') {
            html = `<div class="form-group"><label>متن جداکننده:</label><input type="text" name="content[text]"></div>`;
        } else if (type === 'contact') {
            html = `<div class="form-group"><label>شماره تماس مستقیم:</label><input type="text" name="content[phone]"></div>
                    <div class="form-group"><label>شماره پیامک:</label><input type="text" name="content[sms]"></div>
                    <div class="form-group"><label>ایمیل:</label><input type="text" name="content[email]"></div>`;
        } else if (type === 'location') {
            html = `<div class="form-group"><label>لینک مسیریابی نشان:</label><input type="text" name="content[neshan]"></div>
                    <div class="form-group"><label>لینک مسیریابی بلد:</label><input type="text" name="content[balad]"></div>
                    <div class="form-group"><label>لینک گوگل مپ:</label><input type="text" name="content[gmaps]"></div>
                    <div class="form-group"><label>آدرس متنی:</label><input type="text" name="content[address]"></div>`;
        } else if (type === 'payment') {
            html = `<div class="form-group"><label>نام صاحب حساب:</label><input type="text" name="content[owner]"></div>
                    <div class="form-group"><label>شماره کارت (۱۶ رقمی):</label><input type="text" name="content[card]"></div>
                    <div class="form-group"><label>شماره شبا (IR...):</label><input type="text" name="content[sheba]"></div>
                    <div class="form-group"><label>لینک پرداخت آنلاین:</label><input type="text" name="content[pay_url]"></div>`;
        } else if (type === 'iran_platforms') {
            html = `<div class="form-group"><label>لینک در ترب:</label><input type="text" name="content[torob]"></div>
                    <div class="form-group"><label>لینک در باسلام:</label><input type="text" name="content[basalam]"></div>
                    <div class="form-group"><label>لینک در دیجی‌کالا:</label><input type="text" name="content[digikala]"></div>`;
        } else if (type === 'products') {
            html = `<div class="form-group"><label>نام محصول ۱:</label><input type="text" name="content[p1_title]"></div>
                    <div class="form-group"><label>قیمت محصول ۱ (تومان):</label><input type="text" name="content[p1_price]"></div>
                    <div class="form-group"><label>لینک خرید محصول ۱:</label><input type="text" name="content[p1_url]"></div>`;
        } else if (type === 'lead_form') {
            html = `<div class="form-group"><label>توضیح فرم لید:</label><input type="text" name="content[description]" placeholder="شماره تماس خود را بگذارید تا با شما تماس بگیریم"></div>`;
        } else if (type === 'booking') {
            html = `<div class="form-group"><label>روزهای کاری:</label><input type="text" name="content[days]" value="شنبه تا چهارشنبه"></div>
                    <div class="form-group"><label>ساعات کاری:</label><input type="text" name="content[hours]" value="۹ الی ۱۸"></div>`;
        } else if (type === 'faq') {
            html = `<div class="form-group"><label>سوال ۱:</label><input type="text" name="content[q1]"></div>
                    <div class="form-group"><label>پاسخ ۱:</label><textarea name="content[a1]"></textarea></div>`;
        } else if (type === 'gallery') {
            html = `<div class="form-group"><label>آدرس عکس ۱:</label><input type="text" name="content[img1]"></div>
                    <div class="form-group"><label>آدرس عکس ۲:</label><input type="text" name="content[img2]"></div>`;
        } else if (type === 'countdown') {
            html = `<div class="form-group"><label>تاریخ پایان کمپین (YYYY-MM-DD HH:MM):</label><input type="text" name="content[end_time]" placeholder="2026-12-31 23:59"></div>`;
        }

        df.innerHTML = html;
    }
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
