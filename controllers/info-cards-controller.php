<?php
// controllers/info-cards-controller.php

// <== Include Required Files ==>
include('../db_connect.php');
include('../helpers/image-upload.php');
include('../models/CRUD.php');

// <== Initialize Model ==>
$model = new CRUD($pdo);

// <== Determine Action From Request ==>
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// <== Handle Action Switch ==>
switch ($action) {

    // --------------------------------------
    // <== Fetch Dashboard Info Cards Data ==>
    // --------------------------------------
    case 'fetch':

        // <== Fetch Total Products ==>
        $stmt = $pdo->query("SELECT COUNT(*) AS total_products FROM products");
        $totalProducts = (int) $stmt->fetch()['total_products'];

        // <== Fetch Active Offers Count ==>
        $stmt = $pdo->query("SELECT COUNT(*) AS active_offers FROM offers WHERE status = 'active'");
        $activeOffers = (int) $stmt->fetch()['active_offers'];

        // <== Fetch Today's Orders Count ==>
        $stmt = $pdo->prepare("SELECT COUNT(*) AS todays_orders FROM orders WHERE DATE(date) = CURDATE()");
        $stmt->execute();
        $todaysOrders = (int) $stmt->fetch()['todays_orders'];

        // <== Fetch Total Revenue from Delivered Orders ==>
        $stmt = $pdo->query("SELECT SUM(total) AS total_revenue FROM orders WHERE status = 'delivered'");
        $totalRevenue = (float) ($stmt->fetch()['total_revenue'] ?? 0);

        // <== Fetch Total Orders ==>
        $stmt = $pdo->query("SELECT COUNT(*) AS total_orders FROM orders");
        $totalOrders = (int) $stmt->fetch()['total_orders'];

        // <== Fetch Total Categories ==>
        $stmt = $pdo->query("SELECT COUNT(*) AS total_categories FROM categories");
        $totalCategories = (int) $stmt->fetch()['total_categories'];

        // <== Respond with Info Cards Data ==>
        echo json_encode([
            'success' => true,
            'data' => [
                'total_products'   => $totalProducts,
                'active_offers'    => $activeOffers,
                'todays_orders'    => $todaysOrders,
                'total_revenue'    => $totalRevenue,
                'total_orders'     => $totalOrders,
                'total_categories' => $totalCategories
            ]
        ]);
        break;

    // --------------------------------------
    // <== Handle Invalid or Missing Action ==>
    // --------------------------------------
    default:
        echo json_encode([
            'success' => false,
            'message' => 'Invalid action'
        ]);
        break;
}
