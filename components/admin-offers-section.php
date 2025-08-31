<!-- Orders Section -->
            <div class="content-section">
                <div class="section-header">
                    <h2>Recent Orders</h2>
                    <button class="btn btn-primary" id="refresh-orders">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
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
                        <tbody id="orders-table-body">
                            <!-- Orders will be dynamically inserted here -->
                        </tbody>
                    </table>
                </div>
            </div>