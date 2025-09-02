<?php
// db_connect.php

// --------------------------------------------
// Load Configuration Constants
// --------------------------------------------
include 'config.php';

// --------------------------------------------
// Database Connection Settings
// --------------------------------------------
$host = DB_HOST;
$db   = DB_NAME;
$user = DB_USER;
$pass = DB_PASS;
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// --------------------------------------------
// Initialize PDO Database Connection
// --------------------------------------------
try {
    // <== Create a new PDO connection ==>
    $pdo = new PDO($dsn, $user, $pass);
    
    // <== Set error mode to exceptions ==>
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // ----------------------------------------
    // Handle DB Connection Error Gracefully
    // ----------------------------------------
    $title = "Database Connection Failed";
    $message = $e->getMessage();
    $line = $e->getLine();
    $file = $e->getFile();

    // <== Redirect to custom error handler page ==>
    header("Location: error.php?title=" . urlencode($title) . "&message=" . urlencode($message) . "&line=$line&file=" . urlencode($file));
    exit;
}
?>
