<?php

namespace App\Services;

use ZipArchive;
use Exception;

class ZipAnalyzer
{
    public function analyze(string $zipPath): array
    {
        if (!file_exists($zipPath) || !is_file($zipPath)) {
            throw new Exception("فایل ZIP پیدا نشد.");
        }

        $zip = new ZipArchive();
        $res = $zip->open($zipPath);
        if ($res !== true) {
            throw new Exception("فایل آپلود شده یک آرشیو ZIP معتبر یا سالم نیست. کد خطا: {$res}");
        }

        $numFiles = $zip->numFiles;
        if ($numFiles === 0) {
            $zip->close();
            throw new Exception("فایل ZIP خالی است.");
        }

        $entries = [];
        $hasSymlinks = false;
        $dangerousFiles = [];
        $mainPluginFile = null;
        $pluginHeader = null;
        $hasThemeStyle = false;
        $themeHeader = null;
        $hasFunctionsPhp = false;

        for ($i = 0; $i < $numFiles; $i++) {
            $stat = $zip->statIndex($i);
            $filename = $stat['name'];

            // Security check 1: Path Traversal & ZIP Slip
            if (str_contains($filename, '../') || str_contains($filename, '..\\') || str_starts_with($filename, '/')) {
                $zip->close();
                throw new Exception("خطای امنیتی: مسیر نامعتبر یا Path Traversal در فایل ({$filename}) شناسایی شد.");
            }

            // Security check 2: Dangerous extensions or scripts outside workspace
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if (in_array($ext, ['exe', 'bat', 'sh', 'cmd', 'vbs', 'phtml', 'php5', 'cgi', 'pl'])) {
                $dangerousFiles[] = $filename;
            }

            $entries[] = $filename;

            // Analyze file contents without executing PHP
            $content = $zip->getFromIndex($i);
            if ($content === false) {
                continue;
            }

            // Check for Plugin Header in PHP files
            if ($ext === 'php' && !$mainPluginFile) {
                if (preg_match('/Plugin\s+Name\s*:\s*(.+)/i', $content, $matches)) {
                    $mainPluginFile = $filename;
                    $pluginHeader = $this->parsePluginHeader($content);
                }
            }

            // Check for Theme Header in style.css
            if (basename($filename) === 'style.css') {
                if (preg_match('/Theme\s+Name\s*:\s*(.+)/i', $content, $matches)) {
                    $hasThemeStyle = true;
                    $themeHeader = $this->parseThemeHeader($content);
                }
            }

            if (basename($filename) === 'functions.php') {
                $hasFunctionsPhp = true;
            }
        }

        $zip->close();

        if (!empty($dangerousFiles)) {
            throw new Exception("فایل‌های غیرمجاز یا خطرناک در آرشیو یافت شدند: " . implode(', ', array_slice($dangerousFiles, 0, 5)));
        }

        // Determine Product Type & Metadata
        $productType = 'other';
        $metadata = [];

        if ($mainPluginFile && $pluginHeader) {
            $productType = 'wordpress-plugin';
            $metadata = array_merge($pluginHeader, ['entry_file' => $mainPluginFile]);
        } elseif ($hasThemeStyle && $themeHeader && $hasFunctionsPhp) {
            $productType = 'wordpress-theme';
            $metadata = array_merge($themeHeader, ['entry_file' => 'functions.php']);
        }

        return [
            'valid' => true,
            'file_count' => $numFiles,
            'product_type' => $productType,
            'metadata' => $metadata,
            'main_file' => $mainPluginFile ?? ($hasFunctionsPhp ? 'functions.php' : null),
            'entries_sample' => array_slice($entries, 0, 10),
        ];
    }

    protected function parsePluginHeader(string $content): array
    {
        $header = [
            'name' => '',
            'version' => '1.0.0',
            'description' => '',
            'author' => 'EAFD',
            'text_domain' => '',
            'requires_wp' => '6.0',
            'requires_php' => '8.2',
        ];

        if (preg_match('/Plugin\s+Name\s*:\s*(.+)/i', $content, $m)) $header['name'] = trim($m[1]);
        if (preg_match('/Version\s*:\s*(.+)/i', $content, $m)) $header['version'] = trim($m[1]);
        if (preg_match('/Description\s*:\s*(.+)/i', $content, $m)) $header['description'] = trim($m[1]);
        if (preg_match('/Author\s*:\s*(.+)/i', $content, $m)) $header['author'] = trim($m[1]);
        if (preg_match('/Requires\s+at\s+least\s*:\s*(.+)/i', $content, $m)) $header['requires_wp'] = trim($m[1]);
        if (preg_match('/Requires\s+PHP\s*:\s*(.+)/i', $content, $m)) $header['requires_php'] = trim($m[1]);

        return $header;
    }

    protected function parseThemeHeader(string $content): array
    {
        $header = [
            'name' => '',
            'version' => '1.0.0',
            'description' => '',
            'author' => 'EAFD',
            'requires_wp' => '6.0',
            'requires_php' => '8.2',
        ];

        if (preg_match('/Theme\s+Name\s*:\s*(.+)/i', $content, $m)) $header['name'] = trim($m[1]);
        if (preg_match('/Version\s*:\s*(.+)/i', $content, $m)) $header['version'] = trim($m[1]);
        if (preg_match('/Description\s*:\s*(.+)/i', $content, $m)) $header['description'] = trim($m[1]);
        if (preg_match('/Author\s*:\s*(.+)/i', $content, $m)) $header['author'] = trim($m[1]);
        if (preg_match('/Requires\s+at\s+least\s*:\s*(.+)/i', $content, $m)) $header['requires_wp'] = trim($m[1]);
        if (preg_match('/Requires\s+PHP\s*:\s*(.+)/i', $content, $m)) $header['requires_php'] = trim($m[1]);

        return $header;
    }
}
