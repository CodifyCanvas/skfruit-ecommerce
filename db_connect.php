<?php
include 'config.php';

$host = DB_HOST;
$db   = DB_NAME;
$user = DB_USER;
$pass = DB_PASS;
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    // Create a new PDO connection
    $pdo = new PDO($dsn, $user, $pass);
    
    // Enable error mode to throw exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // Redirect to a custom error page with error details
    $title = "Database Connection Failed";
    $message = $e->getMessage();
    $line = $e->getLine();
    $file = $e->getFile();

    header("Location: error.php?title=" . urlencode($title) . "&message=" . urlencode($message) . "&line=$line&file=" . urlencode($file));
    exit;
}
?>
