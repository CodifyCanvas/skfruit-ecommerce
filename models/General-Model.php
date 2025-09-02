<?php
// models/GeneralModel.php

class GeneralModel
{
    private $pdo;

    // <== Constructor: Inject PDO Connection ==>
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // --------------------------------------------------
    // <== Get categories with product count ==>
    // @return array
    // --------------------------------------------------
    public function fetchCategoriesWithCountProducts(PDO $pdo): array
    {
        $sql = "
        SELECT 
            c.id,
            c.category,
            c.image_path,
            COALESCE(pc.product_count, 0) AS product_count
        FROM categories c
        LEFT JOIN (
            SELECT 
                category_id, 
                COUNT(*) AS product_count
            FROM products
            GROUP BY category_id
        ) pc ON c.id = pc.category_id
    ";

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --------------------------------------------------
    // <== Get products with their best active offer (if any) ==>
    // @return array
    // --------------------------------------------------
    public function fetchProductsWithOfferShort(PDO $pdo): array
    {
        $sql = "
           SELECT 
                p.id,
                p.title AS name,
                p.price,
                p.image_path AS image,
                o.offer_name AS offer_name,
                o.discount_percent,
                o.valid_until,
                ROUND(p.price - (p.price * o.discount_percent / 100), 2) AS discount_price
           FROM products p
            LEFT JOIN (
                SELECT 
                    opj.product_id, 
                    o.offer_name, 
                    o.discount_percent, 
                    o.valid_until, 
                    o.created_at
                FROM offer_products_junction opj
                INNER JOIN offers o ON opj.offer_id = o.id
                WHERE o.status = 'active'
                AND o.created_at = (
                    SELECT MAX(o2.created_at)
                    FROM offer_products_junction opj2
                    INNER JOIN offers o2 ON opj2.offer_id = o2.id
                    WHERE opj2.product_id = opj.product_id AND o2.status = 'active'
                )
            ) o ON p.id = o.product_id
        ";

        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // <== Normalize Offer Structure ==>
        return array_map(function ($row) {
            return [
                'id'    => (int) $row['id'],
                'name'  => $row['name'],
                'price' => (float) $row['price'],
                'image' => ltrim($row['image'], '/'), // Clean image path
                'offer' => $row['offer_name'] ? [
                    'name'             => $row['offer_name'],
                    'discount_percent' => (float) $row['discount_percent'],
                    'discount_price'   => (float) $row['discount_price'],
                    'offer_end_date'   => $row['valid_until']
                ] : (object)[]
            ];
        }, $results);
    }

    // --------------------------------------------------
    // <== Get all orders for admin/orders table ==>
    // @return array
    // --------------------------------------------------
    public function fetchAllOrdersForOrdersTable(PDO $pdo): array
    {
        $sql = "
            SELECT 
                o.id AS id,
                o.customer_name,
                o.date AS order_date,
                COUNT(oi.id) AS items,
                o.total,
                o.status
            FROM orders o
            LEFT JOIN order_items oi ON o.id = oi.order_id
            GROUP BY o.id, o.customer_name, o.date, o.total, o.status
            ORDER BY o.date DESC
        ";

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --------------------------------------------------
    // <== Get specific order and its items by ID ==>
    // @param int $orderId
    // @return array
    // --------------------------------------------------
    public function fetchSpecificOrderById(PDO $pdo, int $orderId): array
    {
        // <== Fetch order data ==>
        $orderSql = "SELECT * FROM orders WHERE id = :order_id";
        $orderStmt = $pdo->prepare($orderSql);
        $orderStmt->execute(['order_id' => $orderId]);
        $order = $orderStmt->fetch(PDO::FETCH_ASSOC);

        // throw or return ['error' => 'Order not found']
        if (!$order) {
            return [];
        }

        // <== Fetch order items with product info ==>
        $itemsSql = "
            SELECT 
                oi.product_id,
                oi.product_name,
                oi.price AS item_price,
                oi.quantity,
                p.image_path,
                p.stock,
                p.description
            FROM order_items oi
            LEFT JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = :order_id
        ";
        $itemsStmt = $pdo->prepare($itemsSql);
        $itemsStmt->execute(['order_id' => $orderId]);
        $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'order' => $order,
            'items' => $items
        ];
    }
}
?>