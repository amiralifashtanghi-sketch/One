<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <meta name="description" content="<?= htmlspecialchars($bio['description'] ?? '') ?>">
    <style>
        :root {
            --bg: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.7);
            --card-border: rgba(255, 255, 255, 0.1);
            --text: #f8fafc;
            --muted: #94a3b8;
            --primary: #3b82f6;
            --accent: #8b5cf6;
            --success: #10b981;
        }

        body.theme-ocean { background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%); }
        body.theme-sunset { background: linear-gradient(135deg, #31103f 0%, #701a75 50%, #831843 100%); }
        body.theme-emerald { background: linear-gradient(135deg, #022c22 0%, #064e3b 100%); }
        body.theme-purple { background: linear-gradient(135deg, #1e1b4b 0%, #4c1d95 100%); }
        body.theme-minimal_light {
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --card-border: #e2e8f0;
            --text: #0f172a;
            --muted: #64748b;
            --primary: #2563eb;
            background: #f1f5f9;
        }

        * { box-sizing: border-box; }
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            margin: 0;
            padding: 2rem 1rem;
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }
        .bio-container {
            width: 100%;
            max-width: 440px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .profile-header {
            text-align: center;
            margin-bottom: 2rem;
            width: 100%;
        }
        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary);
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            margin-bottom: 1rem;
        }
        .bio-title {
            font-size: 1.4rem;
            font-weight: bold;
            margin: 0 0 0.5rem 0;
        }
        .bio-desc {
            font-size: 0.95rem;
            color: var(--muted);
            line-height: 1.5;
            margin: 0;
        }
        .block-list {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .block-card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--card-border);
            border-radius: 14px;
            padding: 1rem 1.25rem;
            width: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
            color: inherit;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .block-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        .block-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: bold;
            font-size: 1rem;
        }
        .social-grid {
            display: flex;
            justify-content: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            color: var(--text);
            font-size: 0.85rem;
            font-weight: bold;
            text-decoration: none;
        }
        .btn-action {
            width: 100%;
            padding: 0.75rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 0.5rem;
        }
        .input-field {
            width: 100%;
            padding: 0.65rem;
            background: rgba(0,0,0,0.2);
            border: 1px solid var(--card-border);
            border-radius: 8px;
            color: var(--text);
            margin-bottom: 0.5rem;
            outline: none;
        }
        .footer-brand {
            margin-top: 3rem;
            font-size: 0.8rem;
            color: var(--muted);
            text-align: center;
        }
    </style>
    <?php if (!empty($bio['custom_css'])): ?>
        <style><?= $bio['custom_css'] ?></style>
    <?php endif; ?>
</head>
<body class="theme-<?= htmlspecialchars($bio['theme'] ?? 'default') ?>">
    <div class="bio-container">
        <!-- Profile Header -->
        <div class="profile-header">
            <?php if (!empty($bio['avatar'])): ?>
                <img src="<?= htmlspecialchars($bio['avatar']) ?>" alt="<?= htmlspecialchars($bio['title'] ?? '') ?>" class="avatar">
            <?php else: ?>
                <div class="avatar" style="display: flex; align-items: center; justify-content: center; background: var(--primary); color: white; font-size: 2.5rem; margin: 0 auto 1rem auto;">
                    <?= mb_substr($bio['title'] ?: $bio['username'], 0, 1, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <h1 class="bio-title"><?= htmlspecialchars($bio['title'] ?: '@' . $bio['username']) ?></h1>
            <?php if (!empty($bio['description'])): ?>
                <p class="bio-desc"><?= nl2br(htmlspecialchars($bio['description'])) ?></p>
            <?php endif; ?>
        </div>

        <!-- Render All 18 Blocks -->
        <div class="block-list">
            <?php foreach ($blocks as $block): ?>
                <?php
                    $content = json_decode($block['content'] ?? '{}', true) ?: [];
                    $type = $block['type'];
                ?>

                <!-- 1. Simple Link & 3. Website -->
                <?php if ($type === 'link' || $type === 'website'): ?>
                    <a href="<?= htmlspecialchars($content['url'] ?? '#') ?>" target="_blank" class="block-card block-btn" onclick="trackClick(<?= $block['id'] ?>)">
                        <span>🌐 <?= htmlspecialchars($block['title']) ?></span>
                        <span>&larr;</span>
                    </a>

                <!-- 2. Image Link -->
                <?php elseif ($type === 'image_link'): ?>
                    <a href="<?= htmlspecialchars($content['url'] ?? '#') ?>" target="_blank" class="block-card" onclick="trackClick(<?= $block['id'] ?>)" style="display: flex; gap: 1rem; align-items: center;">
                        <?php if (!empty($content['image_url'])): ?>
                            <img src="<?= htmlspecialchars($content['image_url']) ?>" style="width: 50px; height: 50px; border-radius: 8px; object-fit: cover;">
                        <?php endif; ?>
                        <div style="flex: 1;">
                            <strong><?= htmlspecialchars($block['title']) ?></strong>
                        </div>
                    </a>

                <!-- 4. Text & Heading -->
                <?php elseif ($type === 'text'): ?>
                    <div class="block-card">
                        <?php if ($block['title']): ?><strong><?= htmlspecialchars($block['title']) ?></strong><?php endif; ?>
                        <p style="margin: 0.5rem 0 0 0; color: var(--muted); font-size: 0.9rem;"><?= nl2br(htmlspecialchars($content['text'] ?? '')) ?></p>
                    </div>

                <!-- 5. Social Media -->
                <?php elseif ($type === 'social'): ?>
                    <div class="social-grid">
                        <?php if (!empty($content['instagram'])): ?>
                            <a href="https://instagram.com/<?= htmlspecialchars($content['instagram']) ?>" target="_blank" class="social-icon">📷 اینستاگرام</a>
                        <?php endif; ?>
                        <?php if (!empty($content['telegram'])): ?>
                            <a href="https://t.me/<?= htmlspecialchars($content['telegram']) ?>" target="_blank" class="social-icon">✈️ تلگرام</a>
                        <?php endif; ?>
                        <?php if (!empty($content['whatsapp'])): ?>
                            <a href="https://wa.me/<?= htmlspecialchars($content['whatsapp']) ?>" target="_blank" class="social-icon">💬 واتساپ</a>
                        <?php endif; ?>
                        <?php if (!empty($content['eitaa'])): ?>
                            <a href="<?= htmlspecialchars($content['eitaa']) ?>" target="_blank" class="social-icon">📲 پیام‌رسان</a>
                        <?php endif; ?>
                    </div>

                <!-- 6. Image / Poster -->
                <?php elseif ($type === 'image'): ?>
                    <div class="block-card" style="padding: 0; overflow: hidden;">
                        <img src="<?= htmlspecialchars($content['image_url'] ?? '') ?>" alt="<?= htmlspecialchars($block['title']) ?>" style="width: 100%; display: block;">
                    </div>

                <!-- 7. Video Player -->
                <?php elseif ($type === 'video'): ?>
                    <div class="block-card">
                        <div style="font-weight: bold; margin-bottom: 0.5rem;">🎥 <?= htmlspecialchars($block['title']) ?></div>
                        <?php if (strpos($content['video_url'] ?? '', 'aparat.com') !== false): ?>
                            <iframe src="<?= htmlspecialchars($content['video_url']) ?>" style="width: 100%; height: 200px; border: none; border-radius: 8px;" allowfullscreen></iframe>
                        <?php else: ?>
                            <video src="<?= htmlspecialchars($content['video_url'] ?? '') ?>" controls style="width: 100%; border-radius: 8px;"></video>
                        <?php endif; ?>
                    </div>

                <!-- 8. Divider -->
                <?php elseif ($type === 'divider'): ?>
                    <div style="text-align: center; margin: 0.5rem 0; border-bottom: 1px solid var(--card-border); line-height: 0.1em; color: var(--muted); font-size: 0.85rem;">
                        <span style="background: var(--bg); padding: 0 10px;"><?= htmlspecialchars($block['title'] ?: ($content['text'] ?? '')) ?></span>
                    </div>

                <!-- 9. Contact -->
                <?php elseif ($type === 'contact'): ?>
                    <div class="block-card" style="display: flex; gap: 0.5rem; flex-wrap: wrap; justify-content: space-between;">
                        <?php if (!empty($content['phone'])): ?>
                            <a href="tel:<?= htmlspecialchars($content['phone']) ?>" class="social-icon" style="flex: 1; text-align: center;">📞 تماس</a>
                        <?php endif; ?>
                        <?php if (!empty($content['sms'])): ?>
                            <a href="sms:<?= htmlspecialchars($content['sms']) ?>" class="social-icon" style="flex: 1; text-align: center;">💬 پیامک</a>
                        <?php endif; ?>
                        <?php if (!empty($content['email'])): ?>
                            <a href="mailto:<?= htmlspecialchars($content['email']) ?>" class="social-icon" style="flex: 1; text-align: center;">✉️ ایمیل</a>
                        <?php endif; ?>
                    </div>

                <!-- 10. Navigation / Location -->
                <?php elseif ($type === 'location'): ?>
                    <div class="block-card">
                        <div style="font-weight: bold; margin-bottom: 0.5rem;">📍 <?= htmlspecialchars($block['title'] ?: 'مسیریابی و آدرس') ?></div>
                        <?php if (!empty($content['address'])): ?>
                            <p style="font-size: 0.85rem; color: var(--muted); margin-bottom: 0.5rem;"><?= htmlspecialchars($content['address']) ?></p>
                        <?php endif; ?>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            <?php if (!empty($content['neshan'])): ?><a href="<?= htmlspecialchars($content['neshan']) ?>" target="_blank" class="social-icon">نشان</a><?php endif; ?>
                            <?php if (!empty($content['balad'])): ?><a href="<?= htmlspecialchars($content['balad']) ?>" target="_blank" class="social-icon">بلد</a><?php endif; ?>
                            <?php if (!empty($content['gmaps'])): ?><a href="<?= htmlspecialchars($content['gmaps']) ?>" target="_blank" class="social-icon">گوگل مپ</a><?php endif; ?>
                        </div>
                    </div>

                <!-- 11. Payment Info -->
                <?php elseif ($type === 'payment'): ?>
                    <div class="block-card">
                        <div style="font-weight: bold; margin-bottom: 0.5rem;">💳 <?= htmlspecialchars($block['title'] ?: 'اطلاعات پرداخت') ?></div>
                        <?php if (!empty($content['owner'])): ?><div style="font-size: 0.85rem; color: var(--muted);">به نام: <?= htmlspecialchars($content['owner']) ?></div><?php endif; ?>
                        <?php if (!empty($content['card'])): ?><div style="font-family: monospace; font-size: 1.1rem; letter-spacing: 2px; margin: 0.5rem 0;"><code><?= htmlspecialchars($content['card']) ?></code></div><?php endif; ?>
                        <?php if (!empty($content['sheba'])): ?><div style="font-size: 0.8rem; color: var(--muted);">شبا: <?= htmlspecialchars($content['sheba']) ?></div><?php endif; ?>
                    </div>

                <!-- 12. Iran Platforms -->
                <?php elseif ($type === 'iran_platforms'): ?>
                    <div class="block-card">
                        <div style="font-weight: bold; margin-bottom: 0.5rem;">🛍️ <?= htmlspecialchars($block['title'] ?: 'فروشگاه‌های ما') ?></div>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            <?php if (!empty($content['torob'])): ?><a href="<?= htmlspecialchars($content['torob']) ?>" target="_blank" class="social-icon">ترب</a><?php endif; ?>
                            <?php if (!empty($content['basalam'])): ?><a href="<?= htmlspecialchars($content['basalam']) ?>" target="_blank" class="social-icon">باسلام</a><?php endif; ?>
                            <?php if (!empty($content['digikala'])): ?><a href="<?= htmlspecialchars($content['digikala']) ?>" target="_blank" class="social-icon">دیجی‌کالا</a><?php endif; ?>
                        </div>
                    </div>

                <!-- 13. Products List -->
                <?php elseif ($type === 'products'): ?>
                    <div class="block-card">
                        <div style="font-weight: bold; margin-bottom: 0.5rem;">🛍️ <?= htmlspecialchars($block['title'] ?: 'محصولات جدید') ?></div>
                        <?php if (!empty($content['p1_title'])): ?>
                            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--card-border); padding-top: 0.5rem; margin-top: 0.5rem;">
                                <div>
                                    <div style="font-size: 0.95rem; font-weight: bold;"><?= htmlspecialchars($content['p1_title']) ?></div>
                                    <div style="font-size: 0.85rem; color: var(--success);"><?= htmlspecialchars($content['p1_price'] ?? '') ?> تومان</div>
                                </div>
                                <?php if (!empty($content['p1_url'])): ?>
                                    <a href="<?= htmlspecialchars($content['p1_url']) ?>" target="_blank" class="social-icon" style="background: var(--primary); color: white;">خرید</a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                <!-- 14. Lead Form -->
                <?php elseif ($type === 'lead_form'): ?>
                    <div class="block-card">
                        <div style="font-weight: bold; margin-bottom: 0.5rem;">📩 <?= htmlspecialchars($block['title'] ?: 'ارسال پیام / درخواست تماس') ?></div>
                        <form onsubmit="submitLead(event, <?= $bio['id'] ?>)">
                            <input type="text" name="name" placeholder="نام شما" class="input-field" required>
                            <input type="text" name="phone" placeholder="شماره همراه" class="input-field" required>
                            <textarea name="message" placeholder="پیام شما..." class="input-field" rows="2"></textarea>
                            <button type="submit" class="btn-action">ارسال درخواست</button>
                        </form>
                    </div>

                <!-- 15. Booking Appointment -->
                <?php elseif ($type === 'booking'): ?>
                    <div class="block-card">
                        <div style="font-weight: bold; margin-bottom: 0.5rem;">📅 <?= htmlspecialchars($block['title'] ?: 'رزرو آنلاین وقت') ?></div>
                        <form onsubmit="submitBooking(event, <?= $bio['id'] ?>)">
                            <input type="text" name="name" placeholder="نام کامل" class="input-field" required>
                            <input type="text" name="phone" placeholder="شماره همراه" class="input-field" required>
                            <input type="date" name="date" class="input-field" required>
                            <input type="text" name="time" placeholder="ساعت پیشنهادی (مثال: ۱۶:۰۰)" class="input-field" required>
                            <button type="submit" class="btn-action">ثبت رزرو نوبت</button>
                        </form>
                    </div>

                <!-- 16. FAQ Accordion -->
                <?php elseif ($type === 'faq'): ?>
                    <div class="block-card">
                        <div style="font-weight: bold; margin-bottom: 0.5rem;">❓ <?= htmlspecialchars($block['title'] ?: 'سوالات متداول') ?></div>
                        <?php if (!empty($content['q1'])): ?>
                            <details style="border-top: 1px solid var(--card-border); padding-top: 0.5rem;">
                                <summary style="cursor: pointer; font-weight: bold; font-size: 0.9rem;"><?= htmlspecialchars($content['q1']) ?></summary>
                                <p style="font-size: 0.85rem; color: var(--muted); margin: 0.5rem 0 0 0;"><?= nl2br(htmlspecialchars($content['a1'] ?? '')) ?></p>
                            </details>
                        <?php endif; ?>
                    </div>

                <!-- 17. Gallery -->
                <?php elseif ($type === 'gallery'): ?>
                    <div class="block-card">
                        <div style="font-weight: bold; margin-bottom: 0.5rem;">🖼️ <?= htmlspecialchars($block['title'] ?: 'گالری تصاویر') ?></div>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem;">
                            <?php if (!empty($content['img1'])): ?><img src="<?= htmlspecialchars($content['img1']) ?>" style="width: 100%; border-radius: 8px; height: 120px; object-fit: cover;"><?php endif; ?>
                            <?php if (!empty($content['img2'])): ?><img src="<?= htmlspecialchars($content['img2']) ?>" style="width: 100%; border-radius: 8px; height: 120px; object-fit: cover;"><?php endif; ?>
                        </div>
                    </div>

                <!-- 18. Countdown Timer -->
                <?php elseif ($type === 'countdown'): ?>
                    <div class="block-card" style="text-align: center;">
                        <div style="font-weight: bold; margin-bottom: 0.5rem;">⏳ <?= htmlspecialchars($block['title'] ?: 'زمان باقی‌مانده کمپین') ?></div>
                        <div style="font-size: 1.2rem; font-weight: bold; color: var(--primary); font-family: monospace;">
                            <?= htmlspecialchars($content['end_time'] ?? 'پایان رویداد') ?>
                        </div>
                    </div>

                <?php else: ?>
                    <div class="block-card">
                        <strong><?= htmlspecialchars($block['title']) ?></strong>
                    </div>
                <?php endif; ?>

            <?php endforeach; ?>
        </div>

        <!-- Footer -->
        <div class="footer-brand">
            قدرت گرفته از سامانه کارت و بیو لینک اختصاصی
        </div>
    </div>

    <script>
        function trackClick(blockId) {
            fetch('/api/track-click', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'block_id=' + blockId
            });
        }

        function submitLead(e, bioId) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            formData.append('bio_page_id', bioId);

            fetch('/api/lead', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.success) form.reset();
            });
        }

        function submitBooking(e, bioId) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            formData.append('bio_page_id', bioId);

            fetch('/api/booking', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.success) form.reset();
            });
        }
    </script>
</body>
</html>
