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
            color: white;
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
        <div class="sidebar">
            <div class="logo">
                <h2>Sunshine Admin</h2>
                <span>Dashboard Panel</span>
            </div>
            <ul class="nav-menu">
                <li><a href="#" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="#"><i class="fas fa-box"></i> Products</a></li>
                <li><a href="#"><i class="fas fa-tags"></i> Offers</a></li>
                <li><a href="#"><i class="fas fa-users"></i> Customers</a></li>
                <li><a href="#"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                <li><a href="#"><i class="fas fa-chart-line"></i> Analytics</a></li>
                <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Dashboard</h1>
                <div class="user-info">
                    <!-- <img src="https://via.placeholder.com/40" alt="User"> -->
                    <span>Admin User</span>
                </div>
            </div>

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

    <!-- Add Product Modal -->
    <!-- <div class="modal" id="product-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Product</h3>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="product-form">
                    <div class="form-group">
                        <label for="product-name">Product Name</label>
                        <input type="text" id="product-name" required>
                    </div>
                    <div class="form-group">
                        <label for="product-category">Category</label>
                        <select id="product-category" required>
                            <option value="">Select Category</option>
                            <option value="electronics">Electronics</option>
                            <option value="clothing">Clothing</option>
                            <option value="home">Home & Kitchen</option>
                            <option value="accessories">Accessories</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="product-price">Price ($)</label>
                        <input type="number" id="product-price" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="product-stock">Stock Quantity</label>
                        <input type="number" id="product-stock" required>
                    </div>
                    <div class="form-group">
                        <label for="product-description">Description</label>
                        <textarea id="product-description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="product-image">Product Image</label>
                        <input type="file" id="product-image">
                    </div>
                </form>
            </div>
            <div class="form-actions">
                <button class="btn btn-danger close-modal">Cancel</button>
                <button class="btn btn-primary" id="save-product">Save Product</button>
            </div>
        </div>
    </div> -->

    <!-- Add Offer Modal -->
    <!-- <div class="modal" id="offer-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Create New Offer</h3>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="offer-form">
                    <div class="form-group">
                        <label for="offer-name">Offer Name</label>
                        <input type="text" id="offer-name" required>
                    </div>
                    <div class="form-group">
                        <label for="offer-discount">Discount (%)</label>
                        <input type="number" id="offer-discount" min="1" max="100" required>
                    </div>
                    <div class="form-group">
                        <label for="offer-products">Select Products</label>
                        <select id="offer-products" multiple>
                            <option value="1001">Wireless Headphones</option>
                            <option value="1002">Smart Watch</option>
                            <option value="1003">Leather Wallet</option>
                            <option value="1004">Bluetooth Speaker</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="offer-start">Start Date</label>
                        <input type="date" id="offer-start" required>
                    </div>
                    <div class="form-group">
                        <label for="offer-end">End Date</label>
                        <input type="date" id="offer-end" required>
                    </div>
                    <div class="form-group">
                        <label for="offer-description">Description</label>
                        <textarea id="offer-description"></textarea>
                    </div>
                </form>
            </div>
            <div class="form-actions">
                <button class="btn btn-danger close-modal">Cancel</button>
                <button class="btn btn-primary" id="save-offer">Create Offer</button>
            </div>
        </div>
    </div> -->

    <script src="./helpers/js-helpers.js"></script>

    <!-- <script>
        // JavaScript for Admin Panel Functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Modal Elements
            const productModal = document.getElementById('product-modal');
            const offerModal = document.getElementById('offer-modal');
            const orderDetailsModal = document.getElementById('order-details-modal');
            const addProductBtn = document.getElementById('add-product-btn');
            const addOfferBtn = document.getElementById('add-offer-btn');
            const refreshOrdersBtn = document.getElementById('refresh-orders');
            const closeModalBtns = document.querySelectorAll('.close-modal');
            const saveProductBtn = document.getElementById('save-product');
            const saveOfferBtn = document.getElementById('save-offer');
            const updateOrderStatusBtn = document.getElementById('update-order-status');
            const ordersTableBody = document.getElementById('orders-table-body');

            // Initialize orders from localStorage
            let orders = JSON.parse(localStorage.getItem('orders')) || [];
            
            // Load orders into the table
            function loadOrders() {
                ordersTableBody.innerHTML = '';
                
                if (orders.length === 0) {
                    ordersTableBody.innerHTML = '<tr><td colspan="7" style="text-align: center;">No orders found</td></tr>';
                    return;
                }
                
                // Sort orders by date (newest first)
                orders.sort((a, b) => new Date(b.date) - new Date(a.date));
                
                orders.forEach((order, index) => {
                    const orderId = `#${(orders.length - index).toString().padStart(5, '0')}`;
                    const orderDate = new Date(order.date).toLocaleDateString();
                    const totalItems = order.items.reduce((sum, item) => sum + item.quantity, 0);
                    
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${orderId}</td>
                        <td>${order.customer.name}</td>
                        <td>${orderDate}</td>
                        <td>${totalItems} items</td>
                        <td>$${order.total.toFixed(2)}</td>
                        <td><span class="status status-${order.status}">${order.status.charAt(0).toUpperCase() + order.status.slice(1)}</span></td>
                        <td class="action-btns">
                            <button class="action-btn btn-primary view-order" data-index="${index}"><i class="fas fa-eye"></i></button>
                            <button class="action-btn btn-danger delete-order" data-index="${index}"><i class="fas fa-trash"></i></button>
                        </td>
                    `;
                    
                    ordersTableBody.appendChild(row);
                });
                
                // Add event listeners to view and delete buttons
                document.querySelectorAll('.view-order').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const index = this.getAttribute('data-index');
                        showOrderDetails(index);
                    });
                });
                
                document.querySelectorAll('.delete-order').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const index = this.getAttribute('data-index');
                        deleteOrder(index);
                    });
                });
                
                // Update dashboard stats
                updateDashboardStats();
            }
            
            // Show order details in modal
            function showOrderDetails(index) {
                const order = orders[index];
                const orderId = `#${(orders.length - index).toString().padStart(5, '0')}`;
                
                document.getElementById('order-details-id').textContent = orderId;
                
                // Display customer information
                document.getElementById('customer-info').innerHTML = `
                    <p><strong>Name:</strong> ${order.customer.name}</p>
                    <p><strong>Email:</strong> ${order.customer.email}</p>
                    <p><strong>Phone:</strong> ${order.customer.phone}</p>
                    <p><strong>Address:</strong> ${order.customer.address}, ${order.customer.country}</p>
                `;
                
                // Display order items
                const orderItemsList = document.getElementById('order-items-list');
                orderItemsList.innerHTML = '';
                
                order.items.forEach(item => {
                    const itemElement = document.createElement('div');
                    itemElement.className = 'order-item';
                    itemElement.innerHTML = `
                        <div class="order-item-info">
                            <span>${item.name} x ${item.quantity}</span>
                        </div>
                        <div>$${(item.price * item.quantity).toFixed(2)}</div>
                    `;
                    orderItemsList.appendChild(itemElement);
                });
                
                // Display order summary
                document.getElementById('order-subtotal').textContent = `$${order.subtotal.toFixed(2)}`;
                document.getElementById('order-shipping').textContent = `$${order.shipping.toFixed(2)}`;
                document.getElementById('order-total').textContent = `$${order.total.toFixed(2)}`;
                
                // Set current status
                document.getElementById('order-status').value = order.status;
                
                // Update button event
                updateOrderStatusBtn.onclick = function() {
                    order.status = document.getElementById('order-status').value;
                    localStorage.setItem('orders', JSON.stringify(orders));
                    loadOrders();
                    orderDetailsModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                };
                
                // Show the modal
                orderDetailsModal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
            
            // Delete an order
            function deleteOrder(index) {
                if (confirm('Are you sure you want to delete this order?')) {
                    orders.splice(index, 1);
                    localStorage.setItem('orders', JSON.stringify(orders));
                    loadOrders();
                }
            }
            
            // Update dashboard statistics
            function updateDashboardStats() {
                // Update today's orders count
                const today = new Date().toDateString();
                const todaysOrders = orders.filter(order => new Date(order.date).toDateString() === today).length;
                document.getElementById('todays-orders').textContent = todaysOrders;
                
                // Update total revenue
                const totalRevenue = orders.reduce((sum, order) => sum + order.total, 0);
                document.getElementById('total-revenue').textContent = `$${totalRevenue.toFixed(2)}`;
            }
            
            // Open Product Modal
            addProductBtn.addEventListener('click', function() {
                productModal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            });

            // Open Offer Modal
            addOfferBtn.addEventListener('click', function() {
                offerModal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            });
            
            // Refresh orders
            refreshOrdersBtn.addEventListener('click', function() {
                orders = JSON.parse(localStorage.getItem('orders')) || [];
                loadOrders();
            });

            // Close Modals
            closeModalBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    productModal.style.display = 'none';
                    offerModal.style.display = 'none';
                    orderDetailsModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                });
            });

            // Close modal when clicking outside
            window.addEventListener('click', function(e) {
                if (e.target === productModal) {
                    productModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
                if (e.target === offerModal) {
                    offerModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
                if (e.target === orderDetailsModal) {
                    orderDetailsModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });

            // Save Product (simulated)
            saveProductBtn.addEventListener('click', function() {
                // In a real app, you would send this data to your server
                alert('Product saved successfully!');
                productModal.style.display = 'none';
                document.body.style.overflow = 'auto';
                // Reset form
                document.getElementById('product-form').reset();
            });

            // Save Offer (simulated)
            saveOfferBtn.addEventListener('click', function() {
                // In a real app, you would send this data to your server
                alert('Offer created successfully!');
                offerModal.style.display = 'none';
                document.body.style.overflow = 'auto';
                // Reset form
                document.getElementById('offer-form').reset();
            });

            // Simulate product delete (in a real app, this would call an API)
            document.querySelectorAll('.action-btns .btn-danger').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    if (confirm('Are you sure you want to delete this item?')) {
                        const row = e.target.closest('tr');
                        row.style.opacity = '0';
                        setTimeout(() => {
                            row.remove();
                        }, 300);
                    }
                });
            });
            
            // Initial load of orders
            loadOrders();
        });
    </script> -->
</body>
</html>