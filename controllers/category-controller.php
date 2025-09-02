<?php
// controllers/category-controller.php

// <== Include Required Files ==>
include('../db_connect.php');
include('../helpers/image-upload.php');
include('../models/CRUD.php');

// <== Initialize Model and Table Name ==>
$model = new CRUD($pdo);
$tableName = 'categories';

// <== Determine Action From Request ==>
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// <== Handle Actions Using Switch ==>
switch ($action) {

    // --------------------------------------
    // <== Create New Category ==>
    // --------------------------------------
    case 'create':
        $name = $_POST['category_name'] ?? '';
        $image = $_FILES['category_image'] ?? null;

        // <== Validate Required Fields ==>
        if (!$name) {
            echo json_encode(['success' => false, 'message' => 'Missing fields']);
            exit;
        }

        // <== Check for Duplicate Category Name ==>
        if ($model->checkDuplicate('category', $name, $tableName)) {
            echo json_encode(['success' => false, 'message' => 'Category name already exists']);
            exit;
        }

        // <== Handle Image Upload If Provided ==>
        $imagePath = null;
        if ($image && $image['tmp_name']) {
            $imagePath = uploadFile($image);
            if (!$imagePath) {
                echo json_encode(['success' => false, 'message' => 'Image upload failed or invalid file']);
                exit;
            }
        }

        // <== Prepare and Insert Data ==>
        $data = [
            'category' => $name,
            'image_path' => $imagePath
        ];

        $success = $model->create($data, $tableName);

        // <== Respond with Status ==>
        echo json_encode(['success' => $success]);
        break;

    // --------------------------------------
    // <== Update Existing Category ==>
    // --------------------------------------
    case 'update':
        $id = $_POST['id'] ?? null;
        $name = $_POST['category_name'] ?? '';
        $image = $_FILES['category_image'] ?? null;

        // <== Validate ID ==>
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Missing ID']);
            exit;
        }

        // <== Fetch Existing Category Record ==>
        $existing = $model->getById('id', $id, $tableName);
        if (!$existing) {
            echo json_encode(['success' => false, 'message' => 'Category not found']);
            exit;
        }

        // <== Keep Existing Image Path Unless Updated ==>
        $imagePath = $existing['image_path'];

        // <== Handle New Image Upload If Provided ==>
        if ($image && $image['tmp_name']) {
            $newImagePath = uploadFile($image);
            if (!$newImagePath) {
                echo json_encode(['success' => false, 'message' => 'Image upload failed or invalid file']);
                exit;
            }
            $imagePath = $newImagePath;
        }

        // <== Prepare and Update Data ==>
        $data = [
            'category' => $name,
            'image_path' => $imagePath
        ];

        $success = $model->update($data, 'id', $id, $tableName);

        // <== Respond with Status ==>
        echo json_encode(['success' => $success]);
        break;

    // --------------------------------------
    // <== Delete Category ==>
    // --------------------------------------
    case 'delete':
        $id = $_POST['id'] ?? null;

        // <== Validate ID ==>
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Missing ID']);
            exit;
        }

        // <== Perform Deletion ==>
        $success = $model->delete('id', $id, $tableName);

        // <== Respond with Status ==>
        echo json_encode(['success' => $success]);
        break;

    // --------------------------------------
    // <== Fetch All Categories ==>
    // --------------------------------------
    case 'fetch':
        $categories = $model->getAll($tableName);
        echo json_encode(['success' => true, 'data' => $categories]);
        break;

    // <== Invalid or Missing Action ==>
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
