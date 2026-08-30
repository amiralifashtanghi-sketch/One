<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;

class AuditController extends Controller {
    public function index(): void {
        $this->render('pages/audit_index', [
            'pageTitle' => 'ابزار هوشمند آنالیز آنلاین سلامت و سئوی وب‌سایت — EAFD',
            'metaDescription' => 'با وارد کردن URL سایت خود، تست‌های واقعی سرعت، SSL، هدرهای امنیتی، متاهای سئو و تگ‌های HTML را به‌صورت لحظه‌ای دریافت کنید.',
            'csrfToken' => Security::generateCSRFToken()
        ]);
    }

    public function analyze(): void {
        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $this->json(['error' => 'نشست نامعتبر است.'], 400);
        }

        $url = Security::sanitizeString($_POST['url'] ?? '');
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            $this->json(['error' => 'آدرس URL وارد شده معتبر نیست. لطفاً با http:// یا https:// وارد کنید.'], 400);
        }

        $parsedUrl = parse_url($url);
        $host = $parsedUrl['host'] ?? '';

        // Anti-SSRF Protection: Block localhost, loopbacks and private IP ranges
        $ip = gethostbyname($host);
        if (
            $host === 'localhost' ||
            filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false
        ) {
            $this->json(['error' => 'آنالیز آدرس‌های شبکه‌های داخلی یا آی‌پی‌های خصوصی مجاز نمی‌باشد.'], 400);
        }

        // Native PHP HTTP Audit Engine
        $start = microtime(true);
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_NOBODY => false,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_USERAGENT => 'EAFD Smart Auditor 1.0 Bot'
        ]);

        $response = curl_exec($ch);
        $duration = round((microtime(true) - $start) * 1000);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        curl_close($ch);

        if (!$response) {
            $this->json(['error' => 'امکان برقراری ارتباط با وب‌سایت مقصد وجود نداشت.'], 500);
        }

        $headersRaw = substr($response, 0, $headerSize);
        $bodyRaw = substr($response, $headerSize);

        $hasSsl = str_starts_with($url, 'https://');
        $hasCsp = stripos($headersRaw, 'content-security-policy') !== false;
        $hasXframe = stripos($headersRaw, 'x-frame-options') !== false;

        $hasH1 = preg_match('/<h1[^>]*>(.*?)<\/h1>/i', $bodyRaw);
        $hasTitle = preg_match('/<title[^>]*>(.*?)<\/title>/i', $bodyRaw, $titleMatches);
        $titleText = $hasTitle ? trim(strip_tags($titleMatches[1])) : null;
        $hasMetaDesc = preg_match('/<meta[^>]*name=["\']description["\'][^>]*content=["\'](.*?)["\']/i', $bodyRaw);

        $performanceScore = $duration < 500 ? 100 : ($duration < 1500 ? 85 : 60);
        $securityScore = ($hasSsl ? 40 : 0) + ($hasCsp ? 30 : 0) + ($hasXframe ? 30 : 0);
        $seoScore = ($hasTitle ? 40 : 0) + ($hasMetaDesc ? 30 : 0) + ($hasH1 ? 30 : 0);

        $this->json([
            'url' => $url,
            'http_code' => $httpCode,
            'response_time_ms' => $duration,
            'scores' => [
                'performance' => $performanceScore,
                'security' => $securityScore,
                'seo' => $seoScore
            ],
            'details' => [
                'has_ssl' => $hasSsl,
                'has_csp' => $hasCsp,
                'has_xframe' => $hasXframe,
                'page_title' => $titleText ?? 'نامشخص',
                'has_meta_desc' => (bool)$hasMetaDesc,
                'has_h1' => (bool)$hasH1
            ]
        ]);
    }
}
