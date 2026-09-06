INSERT INTO users (id, name, email, phone, password_hash, role, is_active) VALUES
(1, 'مدیر ارشد EAFD', 'admin@eafd.ir', '09150591710', '$2y$10$eEInXvJmYv/Q21A/S/G25O./qF.r4W0S9zD2.Gz9z/Nq0mG/kS2d2', 'admin', 1),
(2, 'کاربر نمونه', 'user@eafd.ir', '09123456789', '$2y$10$eEInXvJmYv/Q21A/S/G25O./qF.r4W0S9zD2.Gz9z/Nq0mG/kS2d2', 'customer', 1);

INSERT INTO design_tokens (token_key, token_value, category) VALUES
('--eafd-color-primary', '#0B63D8', 'color'),
('--eafd-color-secondary', '#18D6D8', 'color'),
('--eafd-color-accent', '#FF8A00', 'color'),
('--eafd-color-bg', '#090D16', 'color'),
('--eafd-color-surface', '#121826', 'color'),
('--eafd-color-text', '#F1F5F9', 'color'),
('--eafd-color-text-muted', '#94A3B8', 'color'),
('--eafd-font-family', 'Vazirmatn, sans-serif', 'typography'),
('--eafd-border-radius', '12px', 'layout'),
('--eafd-matrix-enabled', '1', 'background');

INSERT INTO services (id, title, slug, summary, icon, content, features, price_start, sort_order) VALUES
(1, 'طراحی و توسعه وب پلتفرم اختصاصی', 'web-development', 'طراحی و پیاده‌سازی سیستم‌های وب اختصاصی بدون استفاده از قالب‌های آماده و با کدهای کاملاً بهینه شده.', '🌐', 'ما سیستم‌های وب اختصاصی را بر پایه معماری مدرن MVC و PHP سریع پیاده‌سازی می‌کنیم که بالاترین نرخ تبدیل و سرعت نهایی را تضمین می‌کند.', '["کدنویسی ۱۰۰٪ اختصاصی", "امتیاز ۱۰۰/۱۰۰ لایت‌هاوس", "سازگاری کامل با کیبورد و اسکرین‌ریدر"]', '۲۵,۰۰۰,۰۰۰ تومان', 1),
(2, 'بهینه‌سازی کارایی و سرعت (Core Web Vitals)', 'performance-optimization', 'ارتقای امتیاز Lighthouse و بهبود شاخص‌های حیاتی وب به سطح ۱۰۰/۱۰۰ کامل.', '⚡', 'تحلیل خط به خط کدها، حذف درخواست‌های غیرضروری شبکه و تبدیل فونت‌ها و آیکون‌ها به حالت محلی.', '["بارگذاری زیر ۰.۵ ثانیه", "حذف کامل کدهای بدون استفاده", "بهینه‌سازی اولویت LCP و CLS"]', '۱۵,۰۰۰,۰۰۰ تومان', 2),
(3, 'طراحی سیستم مدیریت لایسنس و فروشگاه دیجیتال', 'license-store-development', 'سیستم‌های پیشرفته صدور لایسنس خودکار، تحویل امن فایل و اتصال به درگاه‌های پرداخت ایرانی.', '🔒', 'افزایش امنیت محصولات دیجیتال با صدور کلیدهای یکتا و اعتبارسنجی از طریق API اختصاصی.', '["صدور خودکار پس از پرداخت", "اعتبارسنجی دامنه و آی‌پی", "دانلود با توکن امنیتی کوتاه عمر"]', '۲۰,۰۰۰,۰۰۰ تومان', 3);

INSERT INTO projects (id, title, slug, summary, client_name, technologies, challenge, solution, results, sort_order) VALUES
(1, 'پلتفرم جامع رزرو و خدمات گردشگری کیش هارمونی', 'kish-harmony-platform', 'طراحی اختصاصی قالب و افزونه‌های رزرو رنت‌کار و تفریحات دریایی همراه با فیلتر آنی.', 'مجموعه کیش هارمونی', 'PHP 8.2, WooCommerce, Vanilla JS, Vazirmatn', 'سرعت کند سامانه قبلی و عدم سازگاری کامل با دستگاه‌های موبایل.', 'بازنویسی کامل با معماری سبک اختصاصی، سیستم کش هوشمند و حذف افزونه‌های سنگین.', 'کاهش زمان بارگذاری از ۶.۸ ثانیه به ۰.۴ ثانیه و کسب امتیاز ۱۰۰ لایت‌هاوس.', 1),
(2, 'سیستم ورود پیامکی و ادمین اختصاصی EAFD', 'eafd-sms-admin-system', 'پیاده‌سازی افزونه ورود بدون کلمه عبور و پنل مدیریت شیشه‌ای فوق سریع.', 'آکادمی برزویی', 'PHP, MySQL, SMS.ir v2 API, Glassmorphic CSS', 'پیچیدگی پنل وردپرس برای اپراتورهای ساده و بروز خطا در ورود پیامکی.', 'طراحی پنل ادمین مجزا با بارگذاری آنی و ارسال کد تایید با الگوی سریع.', 'افزایش ۳۰۰ درصدی رضایت اپراتورها و ثبت بیش از ۱۰,۰۰۰ ورود موفق.', 2);

INSERT INTO products (id, title, slug, summary, description, price, type, version, file_path, license_type, max_domains) VALUES
(1, 'افزونه ورود پیامکی و اطلاع‌رسانی EAFD', 'eafd-sms-login', 'افزونه حرفه‌ای ورود با شماره موبایل و کد یکبار مصرف OTP ویژه ووکامرس.', 'این افزونه امکان ثبت‌نام و ورود آنی کاربران با شماره موبایل، پیامک اطلاع‌رسانی سفارشات و صادرات بانک شماره‌ها را فراهم می‌سازد.', 490000, 'plugin', '1.0.0', 'eafd-sms-login.zip', 'lifetime', 1),
(2, 'قالب اختصاصی آکادمی برزویی (EAFD Theme)', 'eafd-theme', 'قالب فوق‌العاده سریع و اختصاصی ووکامرس با استودیو تنظیمات کامل.', 'قالب کاملاً فارسی و RTL با طراحی مدرن Neorphism و کدنویسی استاندارد WCAG 2.2 AA.', 990000, 'theme', '1.0.0', 'eafd-theme.zip', 'lifetime', 1);

INSERT INTO tools (id, title, slug, description, tool_type, config) VALUES
(1, 'محاسبه‌گر نسبت کنتراست رنگ WCAG 2.2', 'wcag-contrast-checker', 'بررسی آنلاین کنتراست رنگ متن و پس‌زمینه بر اساس استانداردهای بین‌المللی دسترس‌پذیری.', 'contrast', '{"bg":"#090D16","text":"#F1F5F9"}'),
(2, 'تولیدکننده کلمات عبور ایمن EAFD', 'password-generator', 'ساخت رمزهای عبور پیچیده و غیرقابل نفوذ بر اساس استانداردهای امنیتی.', 'generator', '{"length":16}');

INSERT INTO quizzes (id, title, slug, description, questions_json) VALUES
(1, 'ارزیابی آمادگی پلتفرم وب شما برای امتیاز ۱۰۰ Lighthouse', 'lighthouse-readiness-quiz', 'با پاسخ به ۵ سوال کوتاه، میزان بهینه‌بودن معماری سایت خود را بسنجید.', '[{"q":"آیا فونت‌های سایت شما از سرور لوکال بارگذاری می‌شوند؟","options":["بله، کاملاً لوکال","خیر، از CDN یا Google Fonts"]},{"q":"آیا صفحات سایت بدون اجرا شدن جاوااسکریپت قابل مطالعه هستند؟","options":["بله، با Progressive Enhancement","خیر، سایت کاملاً وابسته به JS است"]}]');

INSERT INTO pages (id, title, slug, summary, is_published) VALUES
(1, 'صفحه اصلی', 'home', 'صفحه اصلی پلتفرم وب EAFD', 1),
(2, 'درباره ما', 'about', 'معرفی پلتفرم و تیم فنی EAFD', 1),
(3, 'تماس با ما', 'contact', 'راه‌های ارتباطی با مجموعه EAFD', 1);

INSERT INTO page_sections (id, page_id, section_type, settings, sort_order) VALUES
(1, 1, 'hero', '{"title":"پلتفرم وب اختصاصی EAFD","subtitle":"طراحی و معماری وب‌سایت‌های فوق‌سریع، ۱۰۰٪ فارسی و منطبق با استانداردهای WCAG 2.2 AA","cta_text":"شروع پروژه","cta_url":"/contact"}', 1),
(2, 1, 'intro', '{"title":"چرا معماری EAFD؟","content":"ما وب‌سایت‌ها را بدون وابستگی به کتابخانه‌های خارجی و با بالاترین سطح امنیت، کارایی و SEO پیاده‌سازی می‌کنیم."}', 2),
(3, 1, 'services', '{"title":"خدمات تخصصی ما"}', 3),
(4, 1, 'process', '{"title":"مراحل اجرای پروژه"}', 4),
(5, 1, 'projects', '{"title":"نمونه پروژه‌های شاخص"}', 5),
(6, 1, 'lab', '{"title":"آزمایشگاه و ابزارهای آنلاین EAFD"}', 6),
(7, 1, 'cta', '{"title":"آماده تحول در سامانه وب خود هستید؟","btn_text":"درخواست مشاوره رایگان","btn_url":"/contact"}', 7),
(8, 1, 'faq', '{"title":"سوالات متداول"}', 8);
