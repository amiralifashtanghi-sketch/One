<?php
if (!defined('ABSPATH')) {
    exit;
}

class EAFD_SMS_Export {

    public function __construct() {
        add_action('admin_init', array($this, 'handle_export_download'));
    }

    public static function get_all_phone_contacts() {
        $users = get_users([
            'number' => -1,
            'fields' => ['ID', 'user_login', 'display_name', 'user_registered']
        ]);

        $contacts = [];
        foreach ($users as $u) {
            $phone = get_user_meta($u->ID, 'billing_phone', true);
            if (empty($phone)) {
                $phone = get_user_meta($u->ID, 'digits_phone', true);
            }
            if (empty($phone)) {
                $phone = EAFD_Phone_Helper::normalize_phone($u->user_login);
            }

            if ($phone && EAFD_Phone_Helper::is_valid_mobile($phone)) {
                $contacts[] = [
                    'id' => $u->ID,
                    'name' => $u->display_name ?: 'بدون نام',
                    'phone' => EAFD_Phone_Helper::normalize_phone($phone),
                    'date' => date('Y-m-d H:i', strtotime($u->user_registered))
                ];
            }
        }

        return $contacts;
    }

    public static function render_export_page() {
        $contacts = self::get_all_phone_contacts();
        ?>
        <div class="wrap eafd-admin-wrap">
            <h1 class="eafd-admin-title">بانک شماره‌های کاربران و خروجی اکسل/PDF</h1>
            <div class="eafd-badge-tag">تعداد مخاطبین: <?php echo count($contacts); ?></div>

            <div class="eafd-card" style="margin-top: 20px;">
                <h2>📥 دریافت خروجی بانک شماره‌ها</h2>
                <p>شما می‌توانید بانک کامل شماره تلفن‌های همراه کاربران سایت را در ۳ فرمت دریافت نمایید:</p>
                <div style="margin: 20px 0; display: flex; gap: 12px;">
                    <a href="<?php echo esc_url(admin_url('admin.php?page=eafd-sms-export&action=export_csv')); ?>" class="button button-primary button-hero" style="background:#059669; border-color:#059669;">
                        📊 دانلود خروجی CSV (اکسل)
                    </a>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=eafd-sms-export&action=export_xlsx')); ?>" class="button button-primary button-hero" style="background:#0284c7; border-color:#0284c7;">
                        📈 دانلود خروجی XLSX
                    </a>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=eafd-sms-export&action=export_pdf')); ?>" target="_blank" class="button button-primary button-hero" style="background:#dc2626; border-color:#dc2626;">
                        📄 دانلود / پرینت PDF
                    </a>
                </div>
            </div>

            <div class="eafd-card">
                <h2>📋 لیست کاربران و شماره‌ها</h2>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th style="width: 60px;">شناسه</th>
                            <th>نام و نام خانوادگی</th>
                            <th>شماره همراه</th>
                            <th>تاریخ ثبت‌نام</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($contacts)) : ?>
                            <tr><td colspan="4">هیچ شماره تلفن همراه‌ای یافت نشد.</td></tr>
                        <?php else : ?>
                            <?php foreach (array_slice($contacts, 0, 100) as $c) : ?>
                                <tr>
                                    <td><?php echo esc_html($c['id']); ?></td>
                                    <td><?php echo esc_html($c['name']); ?></td>
                                    <td><strong dir="ltr"><?php echo esc_html($c['phone']); ?></strong></td>
                                    <td><?php echo esc_html($c['date']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }

    public function handle_export_download() {
        if (!isset($_GET['page']) || $_GET['page'] !== 'eafd-sms-export' || !isset($_GET['action'])) {
            return;
        }

        if (!current_user_can('manage_options')) {
            wp_die('دسترسی غیرمجاز.');
        }

        $action = sanitize_text_field($_GET['action']);
        $contacts = self::get_all_phone_contacts();

        if ($action === 'export_csv') {
            $this->export_csv($contacts);
        } elseif ($action === 'export_xlsx') {
            $this->export_xlsx($contacts);
        } elseif ($action === 'export_pdf') {
            $this->export_pdf($contacts);
        }
    }

    private function export_csv($contacts) {
        filename_header('contacts-bank-' . date('Y-m-d') . '.csv', 'text/csv');
        // UTF-8 BOM for Persian characters in Excel
        echo "\xEF\xBB\xBF";

        $output = fopen('php://output', 'w');
        fputcsv($output, ['شناسه', 'نام و نام خانوادگی', 'شماره همراه', 'تاریخ ثبت‌نام']);

        foreach ($contacts as $c) {
            fputcsv($output, [$c['id'], $c['name'], $c['phone'], $c['date']]);
        }
        fclose($output);
        exit;
    }

    private function export_xlsx($contacts) {
        filename_header('contacts-bank-' . date('Y-m-d') . '.xls', 'application/vnd.ms-excel');
        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
        <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
        <body dir="rtl">
        <table border="1">
            <tr style="background:#0b63d8; color:#ffffff; font-weight:bold;">
                <th>شناسه</th>
                <th>نام و نام خانوادگی</th>
                <th>شماره همراه</th>
                <th>تاریخ ثبت‌نام</th>
            </tr>';
        foreach ($contacts as $c) {
            echo '<tr>
                <td>' . esc_html($c['id']) . '</td>
                <td>' . esc_html($c['name']) . '</td>
                <td style="mso-number-format:\'\@\';">' . esc_html($c['phone']) . '</td>
                <td>' . esc_html($c['date']) . '</td>
            </tr>';
        }
        echo '</table></body></html>';
        exit;
    }

    private function export_pdf($contacts) {
        header('Content-Type: text/html; charset=utf-8');
        ?>
        <!DOCTYPE html>
        <html lang="fa" dir="rtl">
        <head>
            <meta charset="UTF-8">
            <title>بانک شماره‌های کاربران - <?php echo esc_html(get_bloginfo('name')); ?></title>
            <style>
                @import url('https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css');
                body { font-family: 'Vazirmatn', sans-serif; direction: rtl; padding: 20px; background: #fff; color: #1e293b; }
                h1 { text-align: center; font-size: 20px; margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th, td { border: 1px solid #cbd5e1; padding: 10px; text-align: right; font-size: 13px; }
                th { background: #0b63d8; color: #fff; }
                tr:nth-child(even) { background: #f8fafc; }
                .footer { text-align: center; margin-top: 30px; font-size: 11px; color: #64748b; }
            </style>
        </head>
        <body onload="window.print()">
            <h1>بانک شماره‌های همراه کاربران <?php echo esc_html(get_bloginfo('name')); ?></h1>
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">شناسه</th>
                        <th>نام و نام خانوادگی</th>
                        <th>شماره همراه</th>
                        <th>تاریخ ثبت‌نام</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contacts as $c) : ?>
                        <tr>
                            <td><?php echo esc_html($c['id']); ?></td>
                            <td><?php echo esc_html($c['name']); ?></td>
                            <td dir="ltr"><?php echo esc_html($c['phone']); ?></td>
                            <td><?php echo esc_html($c['date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="footer">تولید شده توسط افزونه ورود پیامکی EAFD.ir</div>
        </body>
        </html>
        <?php
        exit;
    }
}

function filename_header($filename, $type) {
    header('Content-Type: ' . $type . '; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');
}

new EAFD_SMS_Export();
