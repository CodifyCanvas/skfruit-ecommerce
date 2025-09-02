<?php
// controllers/public/product.php

// <== Include Database and Models ==>
include('../../db_connect.php');
include('../../models/CRUD.php');
include('../../models/General-Model.php');

// <== Initialize Model Instances ==>
$MODEL = new CRUD($pdo);
$GENERALMODEL = new GeneralModel($pdo);

// <== Fetch Product by ID: ?id=5 ==>
if (isset($_GET['id'])) {

    // <== Sanitize ID ==>
    $id = (int) $_GET['id'];
    $product = $MODEL->getById('id', $id, 'products');

    if ($product) {
        echo json_encode(['success' => true, 'data' => $product]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
    }
    exit;
}

// <== Fetch Products by Category: ?category_id=2 ==>
if (isset($_GET['category_id'])) {
    $categoryId = $_GET['category_id'];
    $products = $MODEL->getById('category_id', $categoryId, 'products');
    echo json_encode(['success' => true, 'data' => $products]);
    exit;
}

// <== Fetch Short Version of Products: ?short=true (optional: &category_id=2) ==>
if (isset($_GET['short'])) {

    // <== Fetch All Products with Offers (Short View) ==>
    $allProducts = $GENERALMODEL->fetchProductsWithOfferShort($pdo);

    // <== Optionally Filter by Category ID ==>
    if (isset($_GET['category_id'])) {
        $categoryId = $_GET['category_id'];

        // <== Filter Only Products Matching Category ID ==>
        $allProducts = array_filter($allProducts, function ($product) use ($categoryId) {
            return isset($product['category_id']) && (int) $product['category_id'] == $categoryId;
        });

        // <== Reindex the Filtered Array ==>
        $allProducts = array_values($allProducts);
    }

    echo json_encode(['success' => true, 'data' => $allProducts]);
    exit;
}

// <== Handle Invalid Request (No Matching Parameters) ==>
echo json_encode(['success' => false, 'data' => [], 'message' => 'Invalid request' ]);

exit;