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

        /* Main Content Styles */
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

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }

        /* Dashboard Cards */
        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .card:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: var(--primary-dark);
        }

        .card h3 {
            font-size: 14px;
            color: green;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 24px;
            font-weight: 700;
            color: var(--black);
        }

        .card i {
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 40px;
            opacity: 0.2;
            color: var(--primary-dark);
        }

        /* Content Sections */
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

        /* Tables */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-heading{
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

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status-processing {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .action-btns {
            display: flex;
            gap: 5px;
        }

        .action-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding: 15px 20px;
            border-top: 1px solid #eee;
        }

        /* Offer Badge */
        .offer-badge {
            display: inline-block;
            background: var(--primary-dark);
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 5px;
        }

        /* Order Details Modal */
        .order-details-modal .modal-content {
            width: 700px;
        }

        .order-items {
            margin: 15px 0;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .order-item-info {
            display: flex;
            align-items: center;
        }

        .order-item-info img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            margin-right: 15px;
            border-radius: 4px;
        }

        .order-summary {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .summary-total {
            font-weight: bold;
            font-size: 18px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .admin-container {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                height: auto;
            }
            .cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <?php include("./components/admin-sidebar.php") ?>

        <!-- Main Content -->
        <div class="main-content">
            
            <!-- Dashboard Header Bar -->
            <?php include("./components/admin-dashboard-header.php") ?> 
 
            <!-- Dashboard Cards -->
            <?php include("./components/admin-dashboard-cards.php") ?> 

            <!-- Category Section -->
            <?php include './components/admin-category-section.php' ?>

            <!-- Products Section -->
            <?php include './components/admin-products-section.php' ?>

            <!-- Orders Section -->
            <?php include("./components/admin-offers-section.php") ?> 

        </div>
    </div>

    <!-- Order Details Modal -->
    <!-- <div class="modal order-details-modal" id="order-details-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Order Details - <span id="order-details-id"></span></h3>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Customer Information</label>
                    <div id="customer-info"></div>
                </div>
                
                <div class="form-group">
                    <label>Order Items</label>
                    <div class="order-items" id="order-items-list"></div>
                </div>
                
                <div class="order-summary">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span id="order-subtotal">$0.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping:</span>
                        <span id="order-shipping">$0.00</span>
                    </div>
                    <div class="summary-row summary-total">
                        <span>Total:</span>
                        <span id="order-total">$0.00</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="order-status">Order Status</label>
                    <select id="order-status">
                        <option value="processing">Processing</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-danger close-modal">Close</button>
                <button class="btn btn-primary" id="update-order-status">Update Status</button>
            </div>
        </div>
    </div> -->

</body>
</html>