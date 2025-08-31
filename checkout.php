<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - YellowCart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Add these styles to your existing CSS or link a separate CSS file */
        .checkout-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 15px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .checkout-section {
            background-color: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .checkout-title {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .payment-methods {
            margin-top: 20px;
        }

        .payment-method {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .payment-method:hover {
            border-color: var(--primary-color);
        }

        .payment-method.active {
            border-color: var(--primary-color);
            background-color: var(--primary-light);
        }

        .payment-method input {
            margin-right: 10px;
        }

        .order-summary-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .order-total {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        .place-order-btn {
            width: 100%;
            background-color: black;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.3s;
        }
        #place-order-btn {
        display: block !important; /* Force display */
        opacity: 1 !important; /* Ensure fully visible */
        visibility: visible !important; /* Override any hiding */
        }

        .place-order-btn:hover {
            background-color: white;
            color: black;
        }

        @media (max-width: 768px) {
            .checkout-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Include your existing header -->
    <header>
        <!-- Your existing header content -->
    </header>

    <main class="container">
        <div class="checkout-container">
            <div class="checkout-section">
                <h2 class="checkout-title">Shipping Information</h2>
                <form id="checkout-form">
                    <div class="form-group">
                        <label for="full-name">Full Name</label>
                        <input type="text" id="full-name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="address">Street Address</label>
                        <input type="text" id="address" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="country">Country</label>
                        <select id="country" class="form-control" required>
                            <option value="">Select Country</option>
                            <option value="US">United States</option>
                            <option value="CA">Canada</option>
                            <option value="UK">United Kingdom</option>
                            <!-- Add more countries as needed -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" class="form-control" required>
                    </div>

                    <h3 class="checkout-title">Payment Method</h3>
                    <div class="payment-methods">
                        <div class="payment-method">
                            <input type="radio" id="credit-card" name="payment" value="credit-card" checked>
                            <label for="credit-card">Credit Card</label>
                        </div>
                        <div class="payment-method">
                            <input type="radio" id="cash-on-delivery" name="payment" value="cash-on-delivery">
                            <label for="cash-on-delivery">Cash on Delivery</label>
                        </div>
                    </div>

                    <div id="credit-card-fields">
                        <div class="form-group">
                            <label for="card-number">Card Number</label>
                            <input type="text" id="card-number" class="form-control" placeholder="1234 5678 9012 3456">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="expiry">Expiry Date</label>
                                <input type="text" id="expiry" class="form-control" placeholder="MM/YY">
                            </div>
                            <div class="form-group">
                                <label for="cvv">CVV</label>
                                <input type="text" id="cvv" class="form-control" placeholder="123">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="checkout-section">
                <h2 class="checkout-title">Order Summary</h2>
                <div id="order-items">
                    <!-- Cart items will be dynamically inserted here -->
                </div>

                <div class="order-summary-item">
                    <span>Subtotal</span>
                    <span id="subtotal">$0.00</span>
                </div>
                <div class="order-summary-item">
                    <span>Shipping</span>
                    <span id="shipping">$5.99</span>
                </div>
                <div class="order-total">
                    <span>Total</span>
                    <span id="order-total">$0.00</span>
                </div>
                    <button id="place-order-btn" class="place-order-btn">Place Order</button>
            </div>
        </div>
    </main>

    <!-- Include your existing footer -->
    <footer>
        <!-- Your existing footer content -->
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Load cart items from localStorage
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const orderItemsContainer = document.getElementById('order-items');
            const subtotalElement = document.getElementById('subtotal');
            const orderTotalElement = document.getElementById('order-total');
            const shippingCostElement = document.getElementById('shipping');
            const placeOrderBtn = document.getElementById('place-order-btn');
            const creditCardFields = document.getElementById('credit-card-fields');
            
            // Show/hide credit card fields based on payment method
            document.querySelectorAll('input[name="payment"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'credit-card') {
                        creditCardFields.style.display = 'block';
                    } else {
                        creditCardFields.style.display = 'none';
                    }
                });
            });

            // Calculate and display order summary
            function updateOrderSummary() {
                orderItemsContainer.innerHTML = '';
                
                let subtotal = 0;
                
                cart.forEach(item => {
                    const itemTotal = item.price * item.quantity;
                    subtotal += itemTotal;
                    
                    const itemElement = document.createElement('div');
                    itemElement.className = 'order-summary-item';
                    itemElement.innerHTML = `
                        <span>${item.name} (x${item.quantity})</span>
                        <span>$${(itemTotal).toFixed(2)}</span>
                    `;
                    orderItemsContainer.appendChild(itemElement);
                });
                
                const shippingCost = parseFloat(shippingCostElement.textContent.replace('$', ''));
                const total = subtotal + shippingCost;
                
                subtotalElement.textContent = `$${subtotal.toFixed(2)}`;
                orderTotalElement.textContent = `$${total.toFixed(2)}`;
            }
            
            // Handle place order button click
            placeOrderBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const form = document.getElementById('checkout-form');
                const isValid = form.checkValidity();
                
                if (!isValid) {
                    form.reportValidity();
                    return;
                }
                
                // In a real application, you would process the payment here
                // For this demo, we'll just show a success message
                
                // Create order object
                const order = {
                    customer: {
                        name: document.getElementById('full-name').value,
                        email: document.getElementById('email').value,
                        address: document.getElementById('address').value,
                        city: document.getElementById('city').value,
                        zip: document.getElementById('zip').value,
                        country: document.getElementById('country').value,
                        phone: document.getElementById('phone').value
                    },
                    paymentMethod: document.querySelector('input[name="payment"]:checked').value,
                    items: cart,
                    subtotal: parseFloat(subtotalElement.textContent.replace('$', '')),
                    shipping: parseFloat(shippingCostElement.textContent.replace('$', '')),
                    total: parseFloat(orderTotalElement.textContent.replace('$', '')),
                    date: new Date().toISOString(),
                    status: 'processing'
                };
                
                // Save order to localStorage (in a real app, you would send to a server)
                const orders = JSON.parse(localStorage.getItem('orders')) || [];
                orders.push(order);
                localStorage.setItem('orders', JSON.stringify(orders));
                
                // Clear cart
                localStorage.removeItem('cart');
                
                // Redirect to confirmation page
                window.location.href = 'order-confirmation.html';
            });
            
            // Initialize the page
            updateOrderSummary();
        });

        // Add this to your checkout.html JavaScript
function validateForm() {
    const form = document.getElementById('checkout-form');
    const inputs = form.querySelectorAll('input[required], select[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.style.borderColor = 'red';
            isValid = false;
        } else {
            input.style.borderColor = '#ddd';
        }
    });
    
    // Validate email format
    const email = document.getElementById('email');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email.value)) {
        email.style.borderColor = 'red';
        isValid = false;
    }
    
    return isValid;
}

// Update place order button event listener
placeOrderBtn.addEventListener('click', function(e) {
    e.preventDefault();
    
    if (!validateForm()) {
        alert('Please fill out all required fields correctly.');
        return;
    }
    
    // Rest of your existing code...
});
    </script>
</body>
</html>