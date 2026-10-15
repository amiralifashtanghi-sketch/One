<?php

namespace App\Core;

class Cart
{
    protected static string $cartKey = '_shopping_cart';

    public static function getItems(): array
    {
        Session::start();
        return Session::get(self::$cartKey, []);
    }

    public static function add(array $product, int $quantity = 1): void
    {
        Session::start();
        $items = self::getItems();
        $id = $product['id'];

        if (isset($items[$id])) {
            $items[$id]['quantity'] += $quantity;
        } else {
            $items[$id] = [
                'id' => $product['id'],
                'title' => $product['title'],
                'slug' => $product['slug'],
                'price' => (int)$product['price'],
                'quantity' => $quantity,
                'file_path' => $product['file_path'] ?? '',
                'max_domains' => $product['max_domains'] ?? 1,
            ];
        }

        Session::set(self::$cartKey, $items);
    }

    public static function remove(int $productId): void
    {
        Session::start();
        $items = self::getItems();
        if (isset($items[$productId])) {
            unset($items[$productId]);
            Session::set(self::$cartKey, $items);
        }
    }

    public static function clear(): void
    {
        Session::remove(self::$cartKey);
    }

    public static function total(): int
    {
        $items = self::getItems();
        $total = 0;
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    public static function count(): int
    {
        return count(self::getItems());
    }
}
