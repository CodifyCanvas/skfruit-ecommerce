<div class="cards">
    <div class="card">
                    <h3>Active Offers</h3>
                    <p id="active-offers">8</p>
                    <i class="fas fa-tag"></i>
                </div>
                <div class="card">
                    <h3>Total Revenue</h3>
                    <p id="total-revenue">$5,245</p>
                    <i class="fas fa-dollar-sign"></i>
                </div>
    <div class="card">
                    <h3>Total Categories</h3>
                    <p id="total-categories">$5,245</p>
                    <i class="fas fa-th-large"></i>
                </div>
                <div class="card">
                    <h3>Total Products</h3>
                    <p id="total-products">124</p>
                    <i class="fas fa-box"></i>
                </div>
                
                <div class="card">
                    <h3>Today's Orders</h3>
                    <p id="todays-orders">24</p>
                    <i class="fas fa-shopping-cart"></i>
                </div>
                
                <div class="card">
                    <h3>Total Orders</h3>
                    <p id="total-orders">$5,245</p>
                    <i class="fas fa-receipt"></i>
                </div>
                
            </div>

            <script>
document.addEventListener('DOMContentLoaded', function () {
    fetch('controllers/info-cards-controller.php?action=fetch')
        .then(response => response.json())
        .then(result => {
            if (result.success && result.data) {
                document.getElementById('active-offers').textContent = result.data.active_offers;
                document.getElementById('total-revenue').textContent = result.data.total_revenue;
                document.getElementById('total-categories').textContent = result.data.total_categories;
                document.getElementById('total-products').textContent = result.data.total_products;
                document.getElementById('todays-orders').textContent = result.data.todays_orders;
                document.getElementById('total-orders').textContent = result.data.total_orders;

                // Format revenue as currency
                const revenue = parseFloat(result.data.total_revenue).toFixed(2);
                document.getElementById('total-revenue').textContent = `$${revenue}`;
            } else {
                console.error('Failed to load dashboard data:', result.message);
            }
        })
        .catch(error => {
            console.error('Error fetching dashboard stats:', error);
        });
});
</script>
