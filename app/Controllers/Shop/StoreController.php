<?php

namespace App\Controllers\Shop;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Product;

class StoreController extends Controller
{
    protected Product $productModel;

    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
    }

    public function index(Request $request): void
    {
        $products = $this->productModel->where('is_active', 1);
        $this->render('shop.index', ['products' => $products], 'main');
    }

    public function detail(Request $request, array $params): void
    {
        $slug = $params['slug'] ?? '';
        $product = $this->productModel->findBy('slug', $slug);

        if (!$product || !$product['is_active']) {
            \App\Core\ErrorHandler::renderErrorPage(404, "محصول یافت نشد", "محصول دیجیتال مورد نظر موجود نمی‌باشد.");
            exit;
        }

        $this->render('shop.detail', ['product' => $product], 'main');
    }
}
