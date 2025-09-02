<?php
session_start();
include 'config.php';

// Check user session and role
// No user logged in, redirect to login
if (empty($_SESSION['user_id'])) {
    header("Location: " . $baseURL . '/login.php'); 
    exit;
}

// User logged in but not admin, redirect to base URL (homepage)
if ($_SESSION['role'] !== 'admin') {
    header("Location: " . $baseURL);
    exit;
}

// If user_id exists AND role is admin, do nothing (stay on the page)

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sunshine Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./styles/modals.css">
    <link rel="stylesheet" href="./style.css">
    <style>
        :root {
    --primary-color: green;
    --primary-dark: rgb(2, 93, 2);
    --primary-light: rgb(34, 108, 34);
    --text-color: rgb(255, 248, 248);
    --light-text: white;
    --border-color: #e0e0e0;
    --white: #ffffff;
    --black: #000000;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background-color: #f9f9f9;
    color: var(--black);
}

.admin-container {
    display: flex;
    min-height: 100vh;
}
/* Sidebar Styles */
        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-dark));
            color: var(--white);
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            position: relative;
            z-index: 10;
        }

        .logo {
            text-align: center;
            padding: 20px 0;
            margin-bottom: 30px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }

        .logo h2 {
            font-size: 24px;
            font-weight: 700;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
        }

        .logo span {
            display: block;
            font-size: 12px;
            opacity: 0.8;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-menu li {
            margin-bottom: 5px;
        }

        .nav-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--white);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 4px solid transparent;
        }

        .nav-menu a:hover, .nav-menu a.active {
            background-color: rgba(255,255,255,0.2);
            border-left: 4px solid white;
        }

        .nav-menu i {
            margin-right: 10px;
            font-size: 18px;
        }

/* Main Content */
.main-content {
    flex: 1;
    padding: 20px;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.header h1 {
    font-size: 28px;
    color: var(--black);
    position: relative;
}

.header h1:after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -10px;
    width: 50px;
    height: 3px;
    background: var(--primary-light);
}

/* Content Section */
.content-section {
    background: white;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}

.section-header h2 {
    font-size: 20px;
    color: var(--black);
}

/* Buttons */
.btn {
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-primary {
    background: var(--primary-light);
    color: var(--white);
}

.btn-primary:hover {
    background: var(--primary-dark);
}

.btn-danger {
    background: #ff6b6b;
    color: white;
}

.btn-danger:hover {
    background: #ff5252;
}

.btn-success {
    background-color: #4CAF50;
    border: none;
    padding: 8px 16px;
    color: white;
    font-weight: 600;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-success:hover:not(:disabled) {
    background-color: #388e3c;
}

.btn-success:disabled {
    background-color: #a5d6a7;
    cursor: not-allowed;
}

/* Tables */
.table-responsive {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

.table-heading {
    color: white;
}

table th, table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

table th {
    background: var(--primary-light);
    font-weight: 600;
}

table tr:hover {
    background: #fafafa;
}

/* Badges */
.badge {
    padding: 4px 8px;
    font-size: 12px;
    border-radius: 4px;
    color: #fff;
}

.badge.pending {
    background-color: #fd6363;
}

.badge.processing {
    background-color: orange;
}

.badge.delivered {
    background-color: #0c8044;
}

.logo h2 {
            font-size: 24px;
            font-weight: 700;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
        }

        .logo span {
            display: block;
            font-size: 12px;
            opacity: 0.8;
        }

/* Modal */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4);
    justify-content: center;
    align-items: center;
    z-index: 999;
}

.modal {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    width: 90%;
    max-width: 600px;
}
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <?php include("./components/admin-sidebar.php") ?>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header Bar -->
            <?php include("./components/admin-dashboard-header.php") ?> 
            
            <!-- Orders Section -->
            <?php include("./components/admin-orders-section.php") ?> 

        </div>
    </div>

</body>
</html>