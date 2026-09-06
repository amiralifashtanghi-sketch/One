<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Models\Page;
use App\Models\Service;
use App\Models\Project;
use App\Models\Product;

class SitemapController extends Controller
{
    public function xml(Request $request): void
    {
        $baseUrl = "http://" . ($_SERVER['HTTP_HOST'] ?? 'localhost');

        $pageModel = new Page();
        $serviceModel = new Service();
        $projectModel = new Project();
        $productModel = new Product();

        $pages = $pageModel->all();
        $services = $serviceModel->where('is_active', 1);
        $projects = $projectModel->where('is_active', 1);
        $products = $productModel->where('is_active', 1);

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

        // Static / Pages
        foreach ($pages as $p) {
            $url = ($p['slug'] === 'home') ? $baseUrl : "{$baseUrl}/{$p['slug']}";
            $xml .= "  <url><loc>{$url}</loc><priority>0.9</priority></url>\n";
        }

        // Services
        foreach ($services as $s) {
            $xml .= "  <url><loc>{$baseUrl}/services/{$s['slug']}</loc><priority>0.8</priority></url>\n";
        }

        // Projects
        foreach ($projects as $pr) {
            $xml .= "  <url><loc>{$baseUrl}/projects/{$pr['slug']}</loc><priority>0.8</priority></url>\n";
        }

        // Store Products
        foreach ($products as $pd) {
            $xml .= "  <url><loc>{$baseUrl}/store/{$pd['slug']}</loc><priority>0.8</priority></url>\n";
        }

        $xml .= "</urlset>";

        Response::setHeader('Content-Type', 'application/xml; charset=UTF-8');
        echo $xml;
        exit;
    }

    public function robots(Request $request): void
    {
        $baseUrl = "http://" . ($_SERVER['HTTP_HOST'] ?? 'localhost');
        $txt = "User-agent: *\nDisallow: /admin/\nDisallow: /checkout/\nDisallow: /download\nSitemap: {$baseUrl}/sitemap.xml\n";

        Response::setHeader('Content-Type', 'text/plain; charset=UTF-8');
        echo $txt;
        exit;
    }
}
