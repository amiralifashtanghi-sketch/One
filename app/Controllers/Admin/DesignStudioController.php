<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\RBAC;
use App\Core\Session;
use App\Core\Logger;
use App\Models\DesignToken;

class DesignStudioController extends Controller
{
    protected DesignToken $tokenModel;

    public function __construct()
    {
        parent::__construct();
        RBAC::checkPermission('manage_design_studio');
        $this->tokenModel = new DesignToken();
    }

    public function index(Request $request): void
    {
        $tokens = $this->tokenModel->getAllTokens();

        $bg = $tokens['--eafd-color-bg'] ?? '#090D16';
        $text = $tokens['--eafd-color-text'] ?? '#F1F5F9';
        $contrastRatio = $this->calculateContrastRatio($bg, $text);
        $wcagPass = $contrastRatio >= 4.5;

        $this->render('admin.design-studio.index', [
            'tokens' => $tokens,
            'contrastRatio' => round($contrastRatio, 2),
            'wcagPass' => $wcagPass,
        ], 'admin');
    }

    public function update(Request $request): void
    {
        $tokensData = $request->post('tokens', []);

        foreach ($tokensData as $key => $value) {
            if (!str_starts_with($key, '--eafd-')) {
                continue;
            }
            $val = trim((string)$value);
            if (str_contains($key, 'grid-size') || str_contains($key, 'radius')) {
                if ($val !== '' && is_numeric($val)) {
                    $val = $val . 'px';
                }
            }
            $this->tokenModel->setToken($key, $val);
        }

        $this->regenerateCssFile();
        Logger::info("تنظیمات استودیو طراحی با موفقیت به روز گردید.", ['tokens_count' => count($tokensData)]);

        Session::flash('success', 'تنظیمات استودیو طراحی با موفقیت ذخیره گردید و فایل CSS بروزرسانی شد.');
        $this->redirect('/admin/design-studio');
    }

    public function reset(Request $request): void
    {
        $defaults = [
            '--eafd-color-primary' => '#0B63D8',
            '--eafd-color-secondary' => '#18D6D8',
            '--eafd-color-accent' => '#FF8A00',
            '--eafd-color-bg' => '#090D16',
            '--eafd-color-surface' => '#121826',
            '--eafd-color-text' => '#F1F5F9',
            '--eafd-color-text-muted' => '#94A3B8',
            '--eafd-font-family' => "Vazirmatn, sans-serif",
            '--eafd-border-radius' => '12px',
            '--eafd-grid-enabled' => '1',
        ];

        foreach ($defaults as $key => $value) {
            $this->tokenModel->setToken($key, $value);
        }

        $this->regenerateCssFile();
        Session::flash('success', 'تنظیمات استودیو طراحی به حالت پیش‌فرض بازگردانی شد.');
        $this->redirect('/admin/design-studio');
    }

    protected function regenerateCssFile(): void
    {
        $tokens = $this->tokenModel->getAllTokens();
        $cssContent = "/**\n * EAFD Design Tokens Engine (Auto-generated)\n */\n\n:root {\n";

        foreach ($tokens as $key => $value) {
            $cssContent .= "  {$key}: {$value};\n";
        }

        $cssContent .= "}\n";

        $baseDir = defined('EAFD_BASE_DIR') ? EAFD_BASE_DIR : dirname(__DIR__, 3);
        $destPath = $baseDir . '/assets/css/design-tokens.css';
        $dir = dirname($destPath);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        @file_put_contents($destPath, $cssContent, LOCK_EX);
    }

    protected function calculateContrastRatio(string $hex1, string $hex2): float
    {
        $l1 = $this->getLuminance($hex1);
        $l2 = $this->getLuminance($hex2);

        if ($l1 > $l2) {
            return ($l1 + 0.05) / ($l2 + 0.05);
        }
        return ($l2 + 0.05) / ($l1 + 0.05);
    }

    protected function getLuminance(string $hex): float
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        $r = hexdec(substr($hex, 0, 2)) / 255;
        $g = hexdec(substr($hex, 2, 2)) / 255;
        $b = hexdec(substr($hex, 4, 2)) / 255;

        $r = ($r <= 0.03928) ? $r / 12.92 : pow(($r + 0.055) / 1.055, 2.4);
        $g = ($g <= 0.03928) ? $g / 12.92 : pow(($g + 0.055) / 1.055, 2.4);
        $b = ($b <= 0.03928) ? $b / 12.92 : pow(($b + 0.055) / 1.055, 2.4);

        return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
    }
}
