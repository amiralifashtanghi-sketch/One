<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Order extends Model
{
    protected string $table = 'orders';

    public function createOrder(int $userId, array $cartItems, int $totalAmount, string $gateway = 'mock'): array
    {
        $orderNumber = 'EAFD-' . strtoupper(bin2hex(random_bytes(4)));

        Database::query(
            "INSERT INTO orders (user_id, order_number, total_amount, status, gateway, created_at) VALUES (?, ?, ?, 'pending', ?, ?)",
            [$userId, $orderNumber, $totalAmount, $gateway, date('Y-m-d H:i:s')]
        );

        $orderId = (int)Database::lastInsertId();

        foreach ($cartItems as $item) {
            Database::query(
                "INSERT INTO order_items (order_id, product_id, product_title, price) VALUES (?, ?, ?, ?)",
                [$orderId, $item['id'], $item['title'], $item['price']]
            );
        }

        return [
            'id' => $orderId,
            'order_number' => $orderNumber,
            'total_amount' => $totalAmount,
        ];
    }

    public function getOrderItems(int $orderId): array
    {
        return Database::fetchAll("SELECT * FROM order_items WHERE order_id = ?", [$orderId]);
    }
}
