<?php

namespace App\Helpers;

class SeoHelper
{
    public static function renderMeta(string $title, string $description = '', string $canonical = ''): string
    {
        $siteName = 'پلتفرم اختصاصی EAFD';
        $fullTitle = $title ? "{$title} | {$siteName}" : $siteName;
        $desc = $description ?: 'پلتفرم اختصاصی طراحی و توسعه وب‌سایت‌های فوق‌سریع، ۱۰۰٪ فارسی و منطبق با استانداردهای WCAG 2.2 AA.';

        $html = "<title>" . htmlspecialchars($fullTitle) . "</title>\n";
        $html .= '<meta name="description" content="' . htmlspecialchars($desc) . "\">\n";
        $html .= '<meta name="robots" content="index, follow">' . "\n";
        $html .= '<meta property="og:title" content="' . htmlspecialchars($fullTitle) . "\">\n";
        $html .= '<meta property="og:description" content="' . htmlspecialchars($desc) . "\">\n";
        $html .= '<meta property="og:type" content="website">' . "\n";

        if ($canonical) {
            $html .= '<link rel="canonical" href="' . htmlspecialchars($canonical) . "\">\n";
        }

        return $html;
    }

    public static function renderOrganizationJsonLd(): string
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'EAFD Web Platform',
            'url' => 'https://eafd.ir',
            'logo' => 'https://eafd.ir/assets/logo.png',
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => '+98-9150591710',
                'contactType' => 'customer service',
                'areaServed' => 'IR',
                'availableLanguage' => 'Persian',
            ],
        ];

        return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
    }
}
