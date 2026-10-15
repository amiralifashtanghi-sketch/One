<?php

namespace App\Controllers\Shop;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Cart;
use App\Core\Session;
use App\Models\Product;

class CartController extends Controller
{
    public function index(Request $request): void
    {
        $items = Cart::getItems();
        $total = Cart::total();

        $this->render('shop.cart', [
            'items' => $items,
            'total' => $total,
        ], 'main');
    }

    public function add(Request $request, array $params): void
    {
        $productId = (int)$params['id'];
        $productModel = new Product();
        $product = $productModel->find($productId);

        if ($product && $product['is_active']) {
            Cart::add($product, 1);
            Session::flash('success', 'محصول با موفقیت به سبد خرید اضافه گردید.');
        }

        $this->redirect('/cart');
    }

    public function remove(Request $request, array $params): void
    {
        $productId = (int)$params['id'];
        Cart::remove($productId);
        Session::flash('success', 'محصول از سبد خرید حذف شد.');
        $this->redirect('/cart');
    }
}
