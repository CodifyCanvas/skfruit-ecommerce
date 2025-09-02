<?php
// controllers/public/get-order.php

// <== Set JSON Response Header ==>
header('Content-Type: application/json');

// <== Validate and Sanitize GET Parameter (orderId) ==>
if (!isset($_GET['orderId']) || empty($_GET['orderId'])) {
    echo json_encode(['success' => false, 'message' => 'Order ID is required']);
    exit;
}

// <== Typecast to integer for safety ==>
$orderId = (int) $_GET['orderId'];

// <== Include Database Connection ==>
include('../../db_connect.php');

try {
    // <== Fetch Order Details by ID ==>
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    // <== Handle Case: Order Not Found ==>
    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        exit;
    }

    // <== Fetch All Items Belonging to the Order ==>
    $stmtItems = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
    $stmtItems->execute([$orderId]);
    $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

    // <== Return Order and Item Details in JSON Response ==>
    echo json_encode([
        'success' => true,
        'order' => $order,
        'items' => $items
    ]);
} catch (PDOException $e) {
    // <== Handle and Report Database Errors ==>
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
