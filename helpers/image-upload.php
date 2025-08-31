<?php


function uploadFile($file) {
    // Define max file size (1MB in bytes)
    $maxFileSize = 1 * 1024 * 1024; // 1 MB

    // Define the upload directory and base URL
    $uploadDir = __DIR__ . '/../public/uploads/';
    $imgDir = '/SkFruit/public/uploads/'; // Adjust if necessary

    // Create upload directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Validate the file input
    if (!isset($file['error']) || is_array($file['error'])) {
        return false; // Invalid file input
    }

    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false; // File upload error
    }

    // Check file size
    if ($file['size'] > $maxFileSize) {
        return false; // File too large
    }

    // Validate file type (only jpeg, jpg, gif)
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    $validTypes = [
        'image/jpeg',   // covers both jpeg and jpg
        'image/gif',
        'image/png'
    ];
    if (!in_array($mimeType, $validTypes)) {
        return false; // Invalid file type
    }

    // Generate a unique filename
    $filename = uniqid() . '_' . basename($file['name']);
    $targetPath = $uploadDir . $filename;

    // Move the file
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        return false; // Move failed
    }

    // Return the URL to access the file
    return $imgDir . $filename;
}
?>
