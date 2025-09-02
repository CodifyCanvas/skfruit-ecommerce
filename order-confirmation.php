<?php
include('./config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Order Confirmation - YellowCart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0; padding: 0;
        }
        .confirmation-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .confirmation-icon {
            font-size: 60px;
            color: #4CAF50;
            margin-bottom: 20px;
        }
        .confirmation-title {
            font-size: 28px;
            margin-bottom: 15px;
        }
        .confirmation-message {
            font-size: 18px;
            margin-bottom: 30px;
            color: #555;
        }
        .order-details {
            text-align: left;
            margin: 30px 0;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }
        .order-details h3 {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        table.invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 15px;
        }
        table.invoice-table th,
        table.invoice-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        table.invoice-table th {
            background-color: #f2f2f2;
        }
        table.invoice-table tfoot td {
            font-weight: bold;
            border-top: 2px solid #ddd;
        }
        .continue-shopping-btn {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 12px 30px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        .continue-shopping-btn:hover {
            background-color: #388E3C;
        }
    </style>
</head>
<body>
    <main>
        <div class="confirmation-container" id="confirmation-container">
            <div class="confirmation-icon">
                <i class="fas fa-spinner fa-spin"></i>
            </div>
            <p>Loading your order details...</p>
        </div>
    </main>

    <script>
        const baseURL = '<?= $baseURL ?>';
    (async function() {
        const container = document.getElementById('confirmation-container');
        const lastOrder = JSON.parse(localStorage.getItem('lastOrder'));
        if (!lastOrder || !lastOrder.orderId) {
            container.innerHTML = '<p>No recent order found.</p>';
            return;
        }

        try {
            // Fetch order data securely from API endpoint
            const response = await fetch(`${baseURL}/controllers/public/get-order.php?orderId=${encodeURIComponent(lastOrder.orderId)}`);
            const data = await response.json();

            if (!data.success) {
                container.innerHTML = `<p>Failed to load order details: ${data.message || 'Unknown error'}</p>`;
                return;
            }

            const order = data.order;
            const items = data.items;

            // Build the confirmation HTML
            let html = `
                <div class="confirmation-icon"><i class="fas fa-check-circle"></i></div>
                <h1 class="confirmation-title">Thank You for Your Order!</h1>
                <p class="confirmation-message">
                    Your order has been placed successfully. We've sent a confirmation email with your order details.
                </p>
                <div class="order-details">
                    <h3>Order Details</h3>
                    <h2>Order #${order.id}</h2>
                    <p><strong>Name:</strong> ${escapeHtml(order.customer_name)}</p>
                    <p><strong>Email:</strong> ${escapeHtml(order.email)}</p>
                    <p><strong>Address:</strong> ${escapeHtml(order.address)}, ${escapeHtml(order.country)}</p>
                    <p><strong>Phone:</strong> ${escapeHtml(order.phone)}</p>

                    <h3>Order Items:</h3>
                    <table class="invoice-table" aria-label="Order Items">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price (each)</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${items.map(item => `
                                <tr>
                                    <td>${escapeHtml(item.product_name)}</td>
                                    <td>${item.quantity}</td>
                                    <td>$${parseFloat(item.price).toFixed(2)}</td>
                                    <td>$${(item.price * item.quantity).toFixed(2)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" style="text-align:right">Subtotal:</td>
                                <td>$${parseFloat(order.subtotal).toFixed(2)}</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align:right">Shipping:</td>
                                <td>$${parseFloat(order.shipping).toFixed(2)}</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align:right">Total:</td>
                                <td><strong>$${parseFloat(order.total).toFixed(2)}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <a href="${baseURL}/" class="continue-shopping-btn">Continue Shopping</a>
            `;

            container.innerHTML = html;

        } catch (error) {
            container.innerHTML = '<p>Error loading order details. Please try again later.</p>';
            console.error(error);
        }

        // Simple escape function to prevent XSS
        function escapeHtml(text) {
            if (!text) return '';
            return text
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }
    })();
    </script>
</body>
</html>
