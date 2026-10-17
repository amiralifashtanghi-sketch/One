<?php
ob_start();
?>

<h2 style="margin-top: 0; margin-bottom: 1.5rem;">مدیریت و افزودن بلوک‌های صفحه بیو (۱۸ نوع)</h2>

<!-- Section 1: Visual 18-Grid Block Selector -->
<div class="card" style="margin-bottom: 2rem;">
    <h3 style="margin-top: 0; margin-bottom: 1rem; color: var(--primary);">۱. انتخاب نوع بلوک جدید برای افزودن</h3>

    <div class="grid-selector">
        <div class="grid-item" onclick="openAddModal('link', 'لینک ساده')">
            <div class="grid-icon">🔗</div>
            <div class="grid-title">لینک ساده</div>
        </div>
        <div class="grid-item" onclick="openAddModal('image_link', 'لینک تصویری')">
            <div class="grid-icon">🖼️</div>
            <div class="grid-title">لینک تصویری</div>
        </div>
        <div class="grid-item" onclick="openAddModal('website', 'وب‌سایت')">
            <div class="grid-icon">🌐</div>
            <div class="grid-title">وب‌سایت</div>
        </div>
        <div class="grid-item" onclick="openAddModal('text', 'متن و عنوان')">
            <div class="grid-icon">📝</div>
            <div class="grid-title">متن و عنوان</div>
        </div>
        <div class="grid-item" onclick="openAddModal('social', 'شبکه‌های اجتماعی')">
            <div class="grid-icon">💬</div>
            <div class="grid-title">شبکه‌های اجتماعی</div>
        </div>
        <div class="grid-item" onclick="openAddModal('image', 'تصویر و پوستر')">
            <div class="grid-icon">📸</div>
            <div class="grid-title">تصویر و پوستر</div>
        </div>
        <div class="grid-item" onclick="openAddModal('video', 'پلیر ویدیو')">
            <div class="grid-icon">🎥</div>
            <div class="grid-title">پلیر ویدیو</div>
        </div>
        <div class="grid-item" onclick="openAddModal('divider', 'جداکننده')">
            <div class="grid-icon">➖</div>
            <div class="grid-title">جداکننده</div>
        </div>
        <div class="grid-item" onclick="openAddModal('contact', 'تماس و پیام')">
            <div class="grid-icon">📞</div>
            <div class="grid-title">تماس و پیام</div>
        </div>
        <div class="grid-item" onclick="openAddModal('location', 'مسیریابی و آدرس')">
            <div class="grid-icon">📍</div>
            <div class="grid-title">مسیریابی و آدرس</div>
        </div>
        <div class="grid-item" onclick="openAddModal('payment', 'اطلاعات پرداخت')">
            <div class="grid-icon">💳</div>
            <div class="grid-title">اطلاعات پرداخت</div>
        </div>
        <div class="grid-item" onclick="openAddModal('iran_platforms', 'پلتفرم‌های ایران')">
            <div class="grid-icon">🛍️</div>
            <div class="grid-title">پلتفرم‌های ایران</div>
        </div>
        <div class="grid-item" onclick="openAddModal('products', 'لیست محصولات')">
            <div class="grid-icon">📦</div>
            <div class="grid-title">لیست محصولات</div>
        </div>
        <div class="grid-item" onclick="openAddModal('lead_form', 'فرم دریافت اطلاعات')">
            <div class="grid-icon">📩</div>
            <div class="grid-title">فرم دریافت اطلاعات</div>
        </div>
        <div class="grid-item" onclick="openAddModal('booking', 'رزرو وقت و نوبت')">
            <div class="grid-icon">📅</div>
            <div class="grid-title">رزرو وقت و نوبت</div>
        </div>
        <div class="grid-item" onclick="openAddModal('faq', 'سوالات متداول')">
            <div class="grid-icon">❓</div>
            <div class="grid-title">سوالات متداول</div>
        </div>
        <div class="grid-item" onclick="openAddModal('gallery', 'گالری نمونه‌کارها')">
            <div class="grid-icon">🎨</div>
            <div class="grid-title">گالری نمونه‌کارها</div>
        </div>
        <div class="grid-item" onclick="openAddModal('countdown', 'شمارش معکوس')">
            <div class="grid-icon">⏳</div>
            <div class="grid-title">شمارش معکوس</div>
        </div>
    </div>
</div>

<!-- Section 2: List & Edit Added Blocks -->
<div class="card">
    <h3 style="margin-top:0; color: var(--primary);">۲. مدیریت و ویرایش بلوک‌های فعال شما</h3>
    <?php if (empty($blocks)): ?>
        <p style="text-align: center; color: var(--muted); margin: 2rem 0;">هنوز هیچ بلوکی اضافه نکرده‌اید. از شبکه بالای صفحه، بلوک مورد نظر را انتخاب کنید.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ترتیب</th>
                    <th>عنوان بلوک</th>
                    <th>نوع بلوک</th>
                    <th>پیش‌نمایش محتوا / تصویر</th>
                    <th>وضعیت</th>
                    <th>کلیک‌ها</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($blocks as $idx => $block): ?>
                    <?php $blockContent = json_decode($block['content'] ?? '{}', true) ?: []; ?>
                    <tr>
                        <td><?= $idx + 1 ?></td>
                        <td><strong><?= htmlspecialchars($block['title'] ?: 'بدون عنوان') ?></strong></td>
                        <td><code><?= htmlspecialchars($block['type']) ?></code></td>
                        <td>
                            <?php if (!empty($blockContent['image_url'])): ?>
                                <img src="<?= htmlspecialchars($blockContent['image_url']) ?>" style="width: 40px; height: 40px; border-radius: 6px; object-fit: cover;">
                            <?php elseif (!empty($blockContent['url'])): ?>
                                <span style="font-size: 0.8rem; color: var(--muted);"><?= htmlspecialchars($blockContent['url']) ?></span>
                            <?php else: ?>
                                <span style="color: var(--muted);">-</span>
                            <?php endif; ?>
                        </td>
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
                                <button type="button" class="btn" style="padding: 0.35rem 0.6rem; font-size: 0.8rem; background: #2563eb;" onclick="openBlockEditModal(<?= htmlspecialchars(json_encode($block)) ?>)">ویرایش ✏️</button>

                                <form method="POST" action="/dashboard/blocks/toggle" style="display: inline;">
                                    <input type="hidden" name="block_id" value="<?= $block['id'] ?>">
                                    <button type="submit" class="btn" style="padding: 0.35rem 0.6rem; font-size: 0.8rem; background: var(--muted);">
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
<div id="addModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); align-items: center; justify-content: center; z-index: 1000; padding: 1rem;">
    <div style="background: var(--card-bg); border-radius: 16px; padding: 1.5rem; width: 100%; max-width: 550px; max-height: 90vh; overflow-y: auto; border: 1px solid var(--border);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="margin:0;" id="addModalTitle">افزودن بلوک جدید</h3>
            <button type="button" onclick="document.getElementById('addModal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text);">&times;</button>
        </div>

        <form method="POST" action="/dashboard/blocks/add" enctype="multipart/form-data">
            <input type="hidden" name="type" id="add_type_input">

            <div class="form-group">
                <label>عنوان اصلی بلوک:</label>
                <input type="text" name="title" placeholder="عنوان نمایشی بلوک" required>
            </div>

            <!-- Dynamic Fields per Type -->
            <div id="addDynamicFields"></div>

            <button type="submit" class="btn btn-success" style="width: 100%; margin-top: 1rem;">ثبت و افزودن بلوک</button>
        </form>
    </div>
</div>

<!-- Modal Edit Block -->
<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); align-items: center; justify-content: center; z-index: 1000; padding: 1rem;">
    <div style="background: var(--card-bg); border-radius: 16px; padding: 1.5rem; width: 100%; max-width: 550px; max-height: 90vh; overflow-y: auto; border: 1px solid var(--border);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="margin:0;">ویرایش بلوک</h3>
            <button type="button" onclick="document.getElementById('editModal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text);">&times;</button>
        </div>

        <form method="POST" action="/dashboard/blocks/edit" enctype="multipart/form-data">
            <input type="hidden" name="block_id" id="edit_block_id">
            <div class="form-group">
                <label>عنوان اصلی بلوک:</label>
                <input type="text" name="title" id="edit_title" required>
            </div>

            <div id="editDynamicFields"></div>

            <button type="submit" class="btn btn-success" style="width: 100%; margin-top: 1rem;">ذخیره تغییرات بلوک</button>
        </form>
    </div>
</div>

<style>
    .grid-selector {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 0.85rem;
    }
    .grid-item {
        background: #f1f5f9;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1rem 0.5rem;
        text-align: center;
        cursor: pointer;
        transition: transform 0.2s, background 0.2s;
    }
    .grid-item:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
    }
    .grid-icon { font-size: 1.8rem; margin-bottom: 0.35rem; }
    .grid-title { font-size: 0.8rem; font-weight: bold; color: var(--text); }

    @media (max-width: 768px) {
        .grid-selector {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<script>
    function openAddModal(type, typeTitle) {
        document.getElementById('add_type_input').value = type;
        document.getElementById('addModalTitle').innerText = 'افزودن بلوک: ' + typeTitle;

        const df = document.getElementById('addDynamicFields');
        df.innerHTML = getFieldsHtml(type, {});
        document.getElementById('addModal').style.display = 'flex';
    }

    function openBlockEditModal(block) {
        document.getElementById('edit_block_id').value = block.id;
        document.getElementById('edit_title').value = block.title || '';

        let content = {};
        try { content = JSON.parse(block.content || '{}'); } catch(e){}

        const edf = document.getElementById('editDynamicFields');
        edf.innerHTML = getFieldsHtml(block.type, content, true);
        document.getElementById('editModal').style.display = 'flex';
    }

    function getFieldsHtml(type, content, isEdit = false) {
        let html = '';

        if (type === 'link' || type === 'website') {
            html = `<div class="form-group"><label>آدرس لینک (URL):</label><input type="text" name="content[url]" value="${content.url || ''}" placeholder="example.com یا https://..." required></div>`;
        } else if (type === 'image_link') {
            html = `<div class="form-group"><label>آدرس لینک:</label><input type="text" name="content[url]" value="${content.url || ''}" required></div>
                    <div class="form-group"><label>آپلود مستقیم عکس شاخص:</label>${content.image_url ? `<div style="margin-bottom:0.5rem;"><img src="${content.image_url}" style="width:60px; height:60px; border-radius:8px; object-fit:cover;"></div>` : ''}<input type="file" name="image_file" accept="image/*" ${isEdit ? '' : 'required'}></div>`;
        } else if (type === 'text') {
            html = `<div class="form-group"><label>متن کامل / توضیحات:</label><textarea name="content[text]" rows="3" required>${content.text || ''}</textarea></div>`;
        } else if (type === 'social') {
            html = `<div class="form-group"><label>اینستاگرام (آیدی):</label><input type="text" name="content[instagram]" value="${content.instagram || ''}"></div>
                    <div class="form-group"><label>تلگرام (آیدی):</label><input type="text" name="content[telegram]" value="${content.telegram || ''}"></div>
                    <div class="form-group"><label>واتساپ (شماره با ۹۸):</label><input type="text" name="content[whatsapp]" value="${content.whatsapp || ''}"></div>
                    <div class="form-group"><label>ایتا / روبیکا / بله:</label><input type="text" name="content[eitaa]" value="${content.eitaa || ''}"></div>`;
        } else if (type === 'image') {
            html = `<div class="form-group"><label>آپلود مستقیم تصویر / پوستر:</label>${content.image_url ? `<div style="margin-bottom:0.5rem;"><img src="${content.image_url}" style="max-width:100px; border-radius:8px;"></div>` : ''}<input type="file" name="image_file" accept="image/*" ${isEdit ? '' : 'required'}></div>`;
        } else if (type === 'video') {
            html = `<div class="form-group"><label>لینک ویدیو (آپارات یا MP4):</label><input type="text" name="content[video_url]" value="${content.video_url || ''}" required></div>`;
        } else if (type === 'divider') {
            html = `<div class="form-group"><label>متن جداکننده:</label><input type="text" name="content[text]" value="${content.text || ''}"></div>`;
        } else if (type === 'contact') {
            html = `<div class="form-group"><label>شماره تماس مستقیم:</label><input type="text" name="content[phone]" value="${content.phone || ''}"></div>
                    <div class="form-group"><label>شماره پیامک:</label><input type="text" name="content[sms]" value="${content.sms || ''}"></div>
                    <div class="form-group"><label>ایمیل:</label><input type="text" name="content[email]" value="${content.email || ''}"></div>`;
        } else if (type === 'location') {
            html = `<div class="form-group"><label>لینک نشان:</label><input type="text" name="content[neshan]" value="${content.neshan || ''}"></div>
                    <div class="form-group"><label>لینک بلد:</label><input type="text" name="content[balad]" value="${content.balad || ''}"></div>
                    <div class="form-group"><label>لینک گوگل مپ:</label><input type="text" name="content[gmaps]" value="${content.gmaps || ''}"></div>
                    <div class="form-group"><label>آدرس متنی:</label><input type="text" name="content[address]" value="${content.address || ''}"></div>`;
        } else if (type === 'payment') {
            html = `<div class="form-group"><label>نام صاحب حساب:</label><input type="text" name="content[owner]" value="${content.owner || ''}"></div>
                    <div class="form-group"><label>شماره کارت (۱۶ رقمی):</label><input type="text" name="content[card]" value="${content.card || ''}"></div>
                    <div class="form-group"><label>شماره شبا (IR...):</label><input type="text" name="content[sheba]" value="${content.sheba || ''}"></div>`;
        } else if (type === 'iran_platforms') {
            html = `<div class="form-group"><label>لینک ترب:</label><input type="text" name="content[torob]" value="${content.torob || ''}"></div>
                    <div class="form-group"><label>لینک باسلام:</label><input type="text" name="content[basalam]" value="${content.basalam || ''}"></div>
                    <div class="form-group"><label>لینک دیجی‌کالا:</label><input type="text" name="content[digikala]" value="${content.digikala || ''}"></div>`;
        } else if (type === 'products') {
            html = `<div class="form-group"><label>نام محصول:</label><input type="text" name="content[p1_title]" value="${content.p1_title || ''}"></div>
                    <div class="form-group"><label>قیمت محصول (تومان):</label><input type="text" name="content[p1_price]" value="${content.p1_price || ''}"></div>
                    <div class="form-group"><label>لینک خرید:</label><input type="text" name="content[p1_url]" value="${content.p1_url || ''}"></div>`;
        } else if (type === 'lead_form') {
            html = `<div class="form-group"><label>توضیح فرم لید:</label><input type="text" name="content[description]" value="${content.description || ''}" placeholder="شماره تماس خود را بگذارید"></div>`;
        } else if (type === 'booking') {
            html = `<div class="form-group"><label>ساعات و روزهای کاری:</label><input type="text" name="content[days]" value="${content.days || 'شنبه تا چهارشنبه ۹ الی ۱۸'}"></div>`;
        } else if (type === 'faq') {
            html = `<div class="form-group"><label>سوال ۱:</label><input type="text" name="content[q1]" value="${content.q1 || ''}"></div>
                    <div class="form-group"><label>پاسخ ۱:</label><textarea name="content[a1]">${content.a1 || ''}</textarea></div>`;
        } else if (type === 'gallery') {
            html = `<div class="form-group"><label>آپلود عکس نمونه‌کار:</label>${content.img1 ? `<div style="margin-bottom:0.5rem;"><img src="${content.img1}" style="width:60px; height:60px; border-radius:8px; object-fit:cover;"></div>` : ''}<input type="file" name="image_file" accept="image/*"></div>`;
        } else if (type === 'countdown') {
            html = `<div class="form-group"><label>تاریخ و ساعت پایان (مثال: ۱۴۰۵/۱۲/۲۹ ۲۰:۰۰):</label><input type="text" name="content[end_time]" value="${content.end_time || ''}" placeholder="۱۴۰۵/۱۲/۲۹ ۲۰:۰۰"></div>`;
        }

        return html;
    }
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
