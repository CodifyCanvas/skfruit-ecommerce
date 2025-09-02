<?php
// controllers/orders-controller.php

// <== Required Includes ==>
include('../db_connect.php');
include('../helpers/image-upload.php');
include('../models/CRUD.php');
include('../models/General-Model.php');

// <== Initialize Models & Table ==>
$MODEL = new CRUD($pdo);
$GENERALMODEL = new GeneralModel($pdo);
$ordersTable = 'orders';

// <== Get Action from Request ==>
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// <== Switch Based on Action ==>
switch ($action) {

    // --------------------------------------
    // <== UPDATE ORDER STATUS ==>
    // --------------------------------------
    case 'update_status':
    $id = $_POST['id'] ?? null;
    $newStatus = $_POST['status'] ?? null;

    // <== Validate Order ID ==>
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Missing order ID']);
        exit;
    }

    // <== Validate Status ==>
    if (!in_array($newStatus, ['pending', 'processing', 'delivered'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid status']);
        exit;
    }

    // <== Fetch Existing Order ==>
    $existingOrder = $MODEL->getById('id', $id, $ordersTable);
    if (!$existingOrder) {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        exit;
    }

    // <== Prevent Updating Delivered Orders ==>
    if ($existingOrder['status'] === 'delivered') {
        echo json_encode(['success' => false, 'message' => 'Cannot update status of a delivered order']);
        exit;
    }

    // <== Perform Update ==>
    $data = [
        'status' => $newStatus
    ];

    $success = $MODEL->update($data, 'id', $id, $ordersTable);

    echo json_encode(['success' => $success]);
    break;

    // --------------------------------------
    // <== DELETE ORDER ==>
    // --------------------------------------
    case 'delete':
        $id = $_POST['id'] ?? null;

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Missing ID']);
            exit;
        }

        // <== Delete Order ==>
        $success = $MODEL->delete('id', $id, $ordersTable);
        echo json_encode(['success' => $success]);
        break;

    // --------------------------------------
    // <== FETCH ALL ORDERS ==>
    // --------------------------------------
    case 'fetch':
        // <== Get All Orders with Customer/Status Info ==>
        $orders = $GENERALMODEL->fetchAllOrdersForOrdersTable($pdo);

        echo json_encode([
            'success' => true,
            'data' => $orders,
        ]);
        break;

    // --------------------------------------
    // <== VIEW SPECIFIC ORDER ==>
    // --------------------------------------
    case 'view':
        $orderId = $_GET['id'] ?? $_POST['id'] ?? null;

        if (!$orderId) {
            echo json_encode(['success' => false, 'message' => 'Missing order ID']);
            exit;
        }

        // <== Get Order Detail ==>
        $orderDetail = $GENERALMODEL->fetchSpecificOrderById($pdo, (int)$orderId);

        if (!$orderDetail) {
            echo json_encode(['success' => false, 'message' => 'Order not found']);
            exit;
        }

        echo json_encode([
            'success' => true,
            'data' => $orderDetail
        ]);
        break;

    // --------------------------------------
    // <== DEFAULT: INVALID ACTION ==>
    // --------------------------------------
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
?>
