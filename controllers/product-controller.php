<?php
// controllers/product-controller.php

// <== Required Includes ==>
include('../db_connect.php');
include('../helpers/image-upload.php');
include('../models/CRUD.php');

// <== Initialize Model and Tables ==>
$model = new CRUD($pdo);
$productTable = 'products';
$categoryTable = 'categories';

// <== Get Action from Request ==>
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// <== Switch Based on Action ==>
switch ($action) {

    // --------------------------------------
    // <== CREATE PRODUCT ==>
    // --------------------------------------
    case 'create':
        $categoryId = $_POST['product_category'] ?? null;
        $title = $_POST['product_name'] ?? '';
        $description = $_POST['product_description'] ?? '';
        $price = $_POST['product_price'] ?? 0;
        $stock = $_POST['product_stock'] ?? 0;
        $image = $_FILES['product_image'] ?? null;

        // <== Validate Required Fields ==>
        if (!$categoryId || !$title || !$price || !$stock) {
            echo json_encode(['success' => false, 'message' => 'Missing fields']);
            exit;
        }

        // <== Check for Duplicate Product Title ==>
        if ($model->checkDuplicate('title', $title, $productTable)) {
            echo json_encode(['success' => false, 'message' => 'Category name already exists']);
            exit;
        }

        // <== Optional Image Upload ==>
        $imagePath = null;
        if ($image && $image['tmp_name']) {
            $imagePath = uploadFile($image);
            if (!$imagePath) {
                echo json_encode(['success' => false, 'message' => 'Image upload failed or invalid file']);
                exit;
            }
        }

        // <== Prepare Data and Insert ==>
        $data = [
            'category_id' => $categoryId,
            'title' => $title,
            'description' => $description,
            'price' => $price,
            'stock' => $stock,
            'image_path' => $imagePath
        ];

        $success = $model->create($data, $productTable);
        echo json_encode(['success' => $success]);
        break;

    // --------------------------------------
    // <== UPDATE PRODUCT ==>
    // --------------------------------------
    case 'update':
        $id = $_POST['product_id'] ?? null;
        $categoryId = $_POST['product_category'] ?? null;
        $title = $_POST['product_name'] ?? '';
        $description = $_POST['product_description'] ?? '';
        $price = $_POST['product_price'] ?? 0;
        $stock = $_POST['product_stock'] ?? 0;
        $image = $_FILES['product_image'] ?? null;

        // <== Validate ID ==>
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Missing ID']);
            exit;
        }

        // <== Fetch Existing Product ==>
        $existing = $model->getById('id', $id, $productTable);
        if (!$existing) {
            echo json_encode(['success' => false, 'message' => 'Product not found']);
            exit;
        }

        // <== Handle Image Upload (If New Image) ==>
        $imagePath = $existing['image_path'];
        if ($image && $image['tmp_name']) {
            $imagePath = uploadFile($image);
            if (!$imagePath) {
                echo json_encode(['success' => false, 'message' => 'Image upload failed or invalid file']);
                exit;
            }
        }

        // <== Prepare Data and Update ==>
        $data = [
            'category_id' => $categoryId,
            'title' => $title,
            'description' => $description,
            'price' => $price,
            'stock' => $stock,
            'image_path' => $imagePath
        ];

        $success = $model->update($data, 'id', $id, $productTable);
        echo json_encode(['success' => $success]);
        break;

    // --------------------------------------
    // <== DELETE PRODUCT ==>
    // --------------------------------------
    case 'delete':
        $id = $_POST['id'] ?? null;

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Missing ID']);
            exit;
        }

        $success = $model->delete('id', $id, $productTable);
        echo json_encode(['success' => $success]);
        break;

    // --------------------------------------
    // <== FETCH ALL PRODUCTS & CATEGORIES ==>
    // --------------------------------------
    case 'fetch':
        $products = $model->getAll($productTable);
        $categories = $model->getAll($categoryTable);

        echo json_encode([
            'success' => true,
            'data' => $products,
            'categories' => $categories
        ]);
        break;

    // --------------------------------------
    // <== INVALID ACTION ==>
    // --------------------------------------
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
