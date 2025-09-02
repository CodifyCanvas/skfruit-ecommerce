<!-- Orders Panel -->
<div class="content-section">
    <div class="section-header">
        <h2>Orders Management</h2>
    </div>
    <div class="table-responsive">
        <table>
            <thead class="table-heading">
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="order-table-body"></tbody>
        </table>
    </div>
</div>

<!-- View Order Modal -->
<div class="modal-overlay" id="order-view-modal" style="display:none;">
    <div class="modal">
        <div class="modal-header">
            <h2>Order Invoice</h2>
            <button id="close-view-modal" class="close-btn">&times;</button>
        </div>
        <div class="modal-body" id="order-invoice-body">
            <!-- Populated by JS -->
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const orderTableBody = document.getElementById('order-table-body');
        const orderModal = document.getElementById('order-view-modal');
        const closeViewModal = document.getElementById('close-view-modal');
        const invoiceBody = document.getElementById('order-invoice-body');
        const baseURL = '<?= $baseURL ?>';

        function fetchOrders() {
            fetch(`${baseURL}/controllers/orders-controller.php?action=fetch`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) populateOrderTable(data.data);
                });
        }

        function populateOrderTable(orders) {
            orderTableBody.innerHTML = '';
            orders.forEach(order => {
                const row = document.createElement('tr');
                const badge = order.status === 'pending' ?
                    '<span class="badge pending">Pending</span>' :
                    order.status === 'processing' ?
                    '<span class="badge processing">Processing</span>' :
                    '<span class="badge delivered">Delivered</span>';

                row.innerHTML = `
            <td>#${order.id}</td>
            <td>${order.customer_name}</td>
            <td>${order.order_date}</td>
            <td>${order.items}</td>
            <td>$${order.total}</td>
            <td>${badge}</td>
        `;

                const viewBtn = document.createElement('button');
                viewBtn.className = 'btn-primary';
                viewBtn.innerHTML = '<i class="fas fa-eye"></i>';
                Object.assign(viewBtn.style, {
                    padding: '6px 8px',
                    borderRadius: '4px',
                    marginRight: '8px',
                    cursor: 'pointer'
                });
                viewBtn.addEventListener('click', function() {
                    viewOrder(order.id);
                });

                const deleteBtn = document.createElement('button');
                deleteBtn.className = 'btn-danger';
                deleteBtn.innerHTML = '<i class="fas fa-trash"></i>';
                Object.assign(deleteBtn.style, {
                    padding: '6px 8px',
                    borderRadius: '4px',
                    cursor: 'pointer'
                });
                deleteBtn.addEventListener('click', function() {
                    deleteOrder(order.id);
                });

                const actionsTd = document.createElement('td');
                actionsTd.appendChild(viewBtn);
                actionsTd.appendChild(deleteBtn);

                row.appendChild(actionsTd);
                orderTableBody.appendChild(row);
            });
        }

        function viewOrder(orderId) {
            fetch(`${baseURL}/controllers/orders-controller.php?action=view&id=${orderId}`)
                .then(res => res.json())
                .then(response => {
                    if (response.success && response.data) {
                        const {
                            order,
                            items
                        } = response.data;
                        showInvoice(order, items);
                    } else {
                        alert('Failed to fetch order details.');
                    }
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                    alert('Error fetching order.');
                });
        }

        function showInvoice(order, items) {
            const isDelivered = order.status === 'delivered';

            const statusOptions = ['pending', 'processing', 'delivered'].map(status => `
        <option value="${status}" ${status === order.status ? 'selected' : ''}>${status}</option>
    `).join('');

            const itemsHTML = items.map(item => `
        <tr>
            <td>${item.product_name}</td>
            <td>${item.quantity}</td>
            <td>$${parseFloat(item.item_price).toFixed(2)}</td>
            <td>$${(parseFloat(item.item_price) * item.quantity).toFixed(2)}</td>
        </tr>
    `).join('');

            // Inject the HTML without the update button
            invoiceBody.innerHTML = `
    <style>
        #order-invoice-body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.5;
        }
        #order-invoice-body h3 {
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 6px;
            margin-bottom: 15px;
            color: green;
        }
        #order-invoice-body p {
            margin: 4px 0;
        }
        #order-invoice-body table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        #order-invoice-body th, #order-invoice-body td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        #order-invoice-body th {
            background-color: #4CAF50;
            color: white;
            text-transform: uppercase;
            font-weight: 600;
            font-size: 14px;
        }
        #order-invoice-body tbody tr:hover {
            background-color: #f1f9f1;
        }
        #order-invoice-body .totals p {
            font-weight: 600;
            font-size: 16px;
            margin: 8px 0;
        }
        #update-container {
            margin-top: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        #order-status {
            padding: 6px 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
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
    </style>

    <h3>Order #${order.id}</h3>
    <p style="margin-bottom: 6px;"><strong>Customer:</strong> ${order.customer_name}</p>
    <p style="margin-bottom: 6px;"><strong>Email:</strong> ${order.email}</p>
    <p style="margin-bottom: 6px;"><strong>Phone:</strong> ${order.phone}</p>
    <p style="margin-bottom: 6px;"><strong>Address:</strong> ${order.address}, ${order.country}</p>
    <p style="margin-bottom: 6px;"><strong>Payment:</strong> ${order.payment_method}</p>
    <p style="margin-bottom: 20px;"><strong>Order Date:</strong> ${order.date}</p>
    
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>${itemsHTML}</tbody>
    </table>

    <div class="totals">
        <p><strong>Subtotal:</strong> $${parseFloat(order.subtotal).toFixed(2)}</p>
        <p><strong>Shipping:</strong> $${parseFloat(order.shipping).toFixed(2)}</p>
        <p><strong>Total:</strong> $${parseFloat(order.total).toFixed(2)}</p>
    </div>

    <div id="update-container">
        <label for="order-status"><strong>Status:</strong></label>
        <select id="order-status" ${isDelivered ? 'disabled' : ''}>
            ${statusOptions}
        </select>
    </div>
`;


            // Now create and append the update button dynamically
            const updateContainer = document.getElementById('update-container');
            const updateBtn = document.createElement('button');
            updateBtn.className = 'btn-success';
            updateBtn.textContent = 'Update';
            if (isDelivered) updateBtn.disabled = true;

            updateBtn.addEventListener('click', function() {
                updateStatus(order.id);
            });

            updateContainer.appendChild(updateBtn);

            orderModal.style.display = 'flex';
        }


        function updateStatus(orderId) {
            const newStatus = document.getElementById('order-status').value;
            const formData = new FormData();
            formData.append('action', 'update_status');
            formData.append('id', orderId);
            formData.append('status', newStatus);

            fetch(`${baseURL}/controllers/orders-controller.php`, {
                    method: 'POST',
                    body: formData
                }).then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('Status updated!');
                        orderModal.style.display = 'none';
                        fetchOrders();
                    } else {
                        alert('Failed to update status.');
                    }
                });
        }

        function deleteOrder(id) {
            if (!confirm('Are you sure you want to delete this order?')) return;

            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('id', id);

            fetch(`${baseURL}/controllers/orders-controller.php`, {
                    method: 'POST',
                    body: formData
                }).then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('Order deleted!');
                        fetchOrders();
                    } else {
                        alert('Failed to delete order.');
                    }
                });
        }

        closeViewModal.addEventListener('click', () => {
            orderModal.style.display = 'none';
        });

        fetchOrders();
    });
</script>