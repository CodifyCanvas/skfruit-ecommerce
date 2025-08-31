<?php
// controllers/CategoryController.php
include('../db_connect.php'); // Database connection
include('../helpers/image-upload.php'); // File upload helper
include('../models/Category.php'); // Include the Category class

$category = new Category($pdo);
$action = $_POST['action'] ?? $_GET['action'] ?? ''; // Get action from POST or GET

switch ($action) {
    case 'create':
        $name = $_POST['category_name'] ?? '';
        $image = $_FILES['category_image'] ?? null;

        if (!$name) {
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

        // Create category
        $success = $category->create($name, $imagePath);
        echo json_encode(['success' => $success]);
        break;

    case 'update':
        $id = $_POST['id'] ?? null;
        $name = $_POST['category_name'] ?? '';
        $image = $_FILES['category_image'] ?? null;

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Missing ID']);
            exit;
        }

        // Fetch the existing category
        $existing = $category->getById($id);
        if (!$existing) {
            echo json_encode(['success' => false, 'message' => 'Category not found']);
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

        // Update category
        $success = $category->update($id, $name, $imagePath);
        echo json_encode(['success' => $success]);
        break;

    case 'delete':
        $id = $_POST['id'] ?? null;
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Missing ID']);
            exit;
        }

        // Delete category
        $success = $category->delete($id);
        echo json_encode(['success' => $success]);
        break;

    case 'fetch':
        // Fetch all categories
        $categories = $category->getAll();
        echo json_encode(['success' => true, 'data' => $categories]);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
?>
