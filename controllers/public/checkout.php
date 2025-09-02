<?php
// controllers/public/checkout.php

// <== Set JSON Response Header ==>
header('Content-Type: application/json');

// <== Include Dependencies ==>
include('../../db_connect.php');
include('../../models/CRUD.php');

// <== Initialize CRUD Model ==>
$MODEL = new CRUD($pdo);

// <== Check Request Method ==>
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// <== Decode Incoming JSON Payload ==>
$data = json_decode(file_get_contents("php://input"), true);

// <== Validate Required Data ==>
if (empty($data['items']) || empty($data['customer'])) {
    echo json_encode(['success' => false, 'message' => 'Missing checkout data']);
    exit;
}

try {
    // <== Start Database Transaction ==>
    $pdo->beginTransaction();

    // <== Prepare Statement to Insert Order ==>
    $stmt = $pdo->prepare("INSERT INTO orders 
        (customer_name, email, address, country, phone, payment_method, subtotal, shipping, total, date, card_number, expiry, cvv) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // <== Extract Payment Info If Credit Card Method Is Selected ==>
    $cardNumber = $data['paymentMethod'] === 'credit-card' ? $data['paymentDetails']['cardNumber'] : null;
    $expiry = $data['paymentMethod'] === 'credit-card' ? $data['paymentDetails']['expiry'] : null;
    $cvv = $data['paymentMethod'] === 'credit-card' ? $data['paymentDetails']['cvv'] : null;

    // <== Execute Insert Order Query ==>
    $stmt->execute([
        $data['customer']['name'],
        $data['customer']['email'],
        $data['customer']['address'],
        $data['customer']['country'],
        $data['customer']['phone'],
        $data['paymentMethod'],
        $data['subtotal'],
        $data['shipping'],
        $data['total'],
        $data['date'],
        $cardNumber,
        $expiry,
        $cvv
    ]);

    // <== Get Newly Inserted Order ID ==>
    $orderId = $pdo->lastInsertId();

    // <== Prepare Statement to Insert Order Items ==>
    $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES (?, ?, ?, ?, ?)");

    // <== Loop Through Items and Insert Them ==>
    foreach ($data['items'] as $item) {
        $stmtItem->execute([
            $orderId,
            $item['id'],
            $item['name'],
            $item['price'],
            $item['quantity']
        ]);
    }

    // <== Commit Transaction After Successful Inserts ==>
    $pdo->commit();

    // <== Send Success Response with Order ID ==>
    echo json_encode(['success' => true, 'orderId' => $orderId]);
} catch (Exception $e) {
    // <== Rollback If Any Error Occurs During Transaction ==>
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    // <== Send Error Response ==>
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
