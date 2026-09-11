<?php

namespace App\Services;

use ZipArchive;
use Exception;

class LicenseBuilder
{
    protected string $storageDir;
    protected string $tmpDir;

    public function __construct()
    {
        $this->storageDir = EAFD_BASE_DIR . '/storage/private/products';
        $this->tmpDir = EAFD_BASE_DIR . '/storage/tmp_builder';

        if (!is_dir($this->storageDir)) {
            mkdir($this->storageDir, 0755, true);
        }
        if (!is_dir($this->tmpDir)) {
            mkdir($this->tmpDir, 0755, true);
        }
    }

    public function build(int $productId, string $productName, string $version, string $originalZipPath, string $productType, string $entryFile, string $apiUrl): array
    {
        if (!file_exists($originalZipPath)) {
            throw new Exception("فایل اصلی ZIP یافت نشد.");
        }

        $buildId = 'build_' . $productId . '_' . time();
        $workDir = $this->tmpDir . '/' . $buildId;
        mkdir($workDir, 0755, true);

        // 1. Extract original ZIP into workDir
        $zip = new ZipArchive();
        if ($zip->open($originalZipPath) !== true) {
            $this->rrmdir($workDir);
            throw new Exception("امکان باز کردن فایل ZIP وجود ندارد.");
        }
        $zip->extractTo($workDir);
        $zip->close();

        // 2. Locate entry file inside workDir
        $targetFile = $this->findFileInDir($workDir, basename($entryFile));
        if (!$targetFile || !file_exists($targetFile)) {
            $this->rrmdir($workDir);
            throw new Exception("فایل اصلی ورودی ({$entryFile}) در ساختار فایل‌های استخراج شده یافت نشد.");
        }

        // 3. Inject EAFD License SDK
        $sdkDir = dirname($targetFile) . '/eafd-license-sdk';
        if (!is_dir($sdkDir)) {
            mkdir($sdkDir, 0755, true);
        }

        $clientCode = file_get_contents(EAFD_BASE_DIR . '/app/Services/LicenseSDK/ClientTemplate.php');
        file_put_contents($sdkDir . '/class-eafd-license-client.php', $clientCode);

        // Bootstrap snippet to inject at bottom or top of entry file
        $escName = addslashes($productName);
        $escApi = addslashes(rtrim($apiUrl, '/'));

        $bootstrapSnippet = "\n\n/* --- EAFD LICENSE SDK BOOTSTRAP --- */\n";
        $bootstrapSnippet .= "if (!class_exists('EAFD_License_Client') && file_exists(__DIR__ . '/eafd-license-sdk/class-eafd-license-client.php')) {\n";
        $bootstrapSnippet .= "    require_once __DIR__ . '/eafd-license-sdk/class-eafd-license-client.php';\n";
        $bootstrapSnippet .= "}\n";
        $bootstrapSnippet .= "if (class_exists('EAFD_License_Client')) {\n";
        $bootstrapSnippet .= "    global \$eafd_sdk_{$productId};\n";
        $bootstrapSnippet .= "    \$eafd_sdk_{$productId} = new \\EAFD_License_Client('{$productId}', '{$escName}', '{$version}', '{$escApi}');\n";
        $bootstrapSnippet .= "}\n";
        $bootstrapSnippet .= "/* --- END EAFD LICENSE SDK BOOTSTRAP --- */\n";

        $originalContent = file_get_contents($targetFile);
        if (!str_contains($originalContent, 'EAFD LICENSE SDK BOOTSTRAP')) {
            file_put_contents($targetFile, $originalContent . $bootstrapSnippet);
        }

        // 4. Create Protected ZIP Archive
        $productPrivateDir = $this->storageDir . '/' . $productId . '/v_' . $version;
        if (!is_dir($productPrivateDir)) {
            mkdir($productPrivateDir, 0755, true);
        }

        $protectedZipPath = $productPrivateDir . '/protected.zip';
        $this->createZipFromDir($workDir, $protectedZipPath);

        // Copy Original ZIP as reference
        $originalSavePath = $productPrivateDir . '/original.zip';
        copy($originalZipPath, $originalSavePath);

        // 5. Cleanup workspace
        $this->rrmdir($workDir);

        return [
            'success' => true,
            'protected_zip' => $protectedZipPath,
            'original_zip' => $originalSavePath,
            'version' => $version,
        ];
    }

    protected function findFileInDir(string $dir, string $filename): ?string
    {
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
        foreach ($files as $file) {
            if ($file->isFile() && $file->getFilename() === $filename) {
                return $file->getPathname();
            }
        }
        return null;
    }

    protected function createZipFromDir(string $sourceDir, string $outZipPath): void
    {
        $zip = new ZipArchive();
        if ($zip->open($outZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new Exception("ایجاد فایل ZIP خروجی ناموفق بود.");
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($sourceDir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen(realpath($sourceDir)) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();
    }

    protected function rrmdir(string $dir): void
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . "/" . $object) && !is_link($dir . "/" . $object)) {
                        $this->rrmdir($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }
            rmdir($dir);
        }
    }
}
