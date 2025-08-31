<?php
// controllers/CategoryController.php
include('../db_connect.php'); // Database connection
include('../helpers/image-upload.php'); // File upload helper
include('../models/Product.php'); // Include the Category class
include('../models/Category.php'); // Include the Category class

$product = new Product($pdo);
$category = new Category($pdo);

$action = $_POST['action'] ?? $_GET['action'] ?? ''; // Get action from POST or GET

switch ($action) {
    case 'create':
        $categoryId = $_POST['product_category'] ?? null;
        $title = $_POST['product_name'] ?? '';
        $description = $_POST['product_description'] ?? '';
        $price = $_POST['product_price'] ?? 0;
        $stock = $_POST['product_stock'] ?? 0;
        $image = $_FILES['product_image'] ?? null;

        if (!$categoryId || !$title || !$price || !$stock) {
            echo json_encode(['success' => false, 'message' => 'Missing fields']);
            exit;
        }

        // If there's an image, upload it, else set $imagePath to null
        $imagePath = null;
        if ($image && $image['tmp_name']) {
            $imagePath = uploadFile($image);

            if (!$imagePath) {
                echo json_encode(['success' => false, 'message' => 'Image upload failed or invalid file']);
                exit;
            }
        }

        // Create product
        $success = $product->create($categoryId, $title, $description, $price, $stock, $imagePath);
        echo json_encode(['success' => $success]);
        break;

    case 'update':
        $id = $_POST['product_id'] ?? null;
        $categoryId = $_POST['product_category'] ?? null;
        $title = $_POST['product_name'] ?? '';
        $description = $_POST['product_description'] ?? '';
        $price = $_POST['product_price'] ?? 0;
        $stock = $_POST['product_stock'] ?? 0;
        $image = $_FILES['product_image'] ?? null;

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Missing ID']);
            exit;
        }

        // Fetch the existing product
        $existing = $product->getById($id);
        if (!$existing) {
            echo json_encode(['success' => false, 'message' => 'Product not found']);
            exit;
        }

        // Retain the existing image if no new image is uploaded
        $imagePath = $existing['image_path'];

        // Handle new image upload
        if ($image && $image['tmp_name']) {
            $imagePath = uploadFile($image);

            if (!$imagePath) {
                echo json_encode(['success' => false, 'message' => 'Image upload failed or invalid file']);
                exit;
            }
        }

        // Update product
        $success = $product->update($id, $categoryId, $title, $description, $stock, $price, $imagePath);
        echo json_encode(['success' => $success]);
        break;

    case 'delete':
        $id = $_POST['id'] ?? null;
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Missing ID']);
            exit;
        }

        // Delete product
        $success = $product->delete($id);
        echo json_encode(['success' => $success]);
        break;

    case 'fetch':
        // Fetch all products
        $products = $product->getAll();
        $categories = $category->getAll();
        echo json_encode(['success' => true, 'data' => $products, 'categories' => $categories]);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
?>
