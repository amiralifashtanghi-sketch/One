<?php

return [
    'hero' => [
        'name' => 'بخش الهام‌بخش اصلی (Hero Banner)',
        'fields' => [
            'title' => ['type' => 'text', 'label' => 'عنوان اصلی'],
            'subtitle' => ['type' => 'textarea', 'label' => 'توضیحات زیرعنوان'],
            'cta_text' => ['type' => 'text', 'label' => 'متن دکمه اقدام (CTA)'],
            'cta_url' => ['type' => 'text', 'label' => 'لینک دکمه اقدام'],
        ],
    ],
    'intro' => [
        'name' => 'کارت معرفی برند (Intro)',
        'fields' => [
            'title' => ['type' => 'text', 'label' => 'عنوان معرفی'],
            'content' => ['type' => 'textarea', 'label' => 'متن توضیحات اختصاصی'],
        ],
    ],
    'services' => [
        'name' => 'شبکه کارت خدمات (Services Grid)',
        'fields' => [
            'title' => ['type' => 'text', 'label' => 'عنوان بخش خدمات'],
        ],
    ],
    'process' => [
        'name' => 'خط زمانی مراحل اجرای پروژه (Process Timeline)',
        'fields' => [
            'title' => ['type' => 'text', 'label' => 'عنوان مراحل کار'],
        ],
    ],
    'projects' => [
        'name' => 'گالری نمونه پروژه‌های شاخص (Portfolio)',
        'fields' => [
            'title' => ['type' => 'text', 'label' => 'عنوان نمونه‌کارها'],
        ],
    ],
    'lab' => [
        'name' => 'ویترین آزمایشگاه و ابزارهای تعاملی (LAB)',
        'fields' => [
            'title' => ['type' => 'text', 'label' => 'عنوان بخش آزمایشگاه'],
        ],
    ],
    'cta' => [
        'name' => 'بنر تبدیل و مشاوره (CTA Banner)',
        'fields' => [
            'title' => ['type' => 'text', 'label' => 'عنوان بنر'],
            'btn_text' => ['type' => 'text', 'label' => 'متن دکمه'],
            'btn_url' => ['type' => 'text', 'label' => 'لینک دکمه'],
        ],
    ],
    'faq' => [
        'name' => 'آکاردئون سوالات متداول (FAQ Accordion)',
        'fields' => [
            'title' => ['type' => 'text', 'label' => 'عنوان سوالات متداول'],
        ],
    ],
];
