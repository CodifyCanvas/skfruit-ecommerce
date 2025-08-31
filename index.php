<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YellowCart - Fresh Groceries</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            color: var(--text-color);
            background-color: #f9f9f9;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* Header Styles */
        header {
            background-color: var(--white);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: var(--black);
            display: flex;
            align-items: center;
        }

        .logo i {
            margin-right: 10px;
        }

        .logo span {
            color: var(--black);
        }

        .search-bar {
            display: flex;
            align-items: center;
            flex: 1;
            max-width: 500px;
            margin: 0 20px;
        }

        .search-bar input {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: 4px 0 0 4px;
            outline: none;
            font-size: 14px;
        }

        .search-bar button {
            background-color: var(--primary-color);
            color: var(--black);
            border: none;
            padding: 10px 15px;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-bar button:hover {
            background-color: var(--primary-dark);
        }

        .user-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-actions a {
            color: var(--black);
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 12px;
            position: relative;
        }

        .user-actions i {
            font-size: 20px;
            margin-bottom: 5px;
        }

        .cart-count {
            background-color: var(--primary-color);
            color: var(--white);
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: -5px;
            right: -5px;
        }

        /* Navigation */
        nav {
            background-color: var(--primary-light);
            padding: 10px 0;
            position: relative;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 20px;
        }

        .nav-links a {
            color: var(--text-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: var(--primary-dark);
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: var(--text-color);
        }

        /* Hero Section */
        .hero {
            background-color: var(--primary-light);
            padding: 60px 0;
            text-align: center;
            background-image: url('https://images.unsplash.com/photo-1542838132-92c53300491e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            background-blend-mode: overlay;
            color: var(--white);
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.6);
            border-radius: 8px;
        }

        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 1.1rem;
            margin-bottom: 30px;
            color: #eee;
        }

        .cta-button {
            background-color: var(--primary-color);
            color: var(--white);
            border: none;
            padding: 12px 30px;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .cta-button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Categories */
        .section-title {
            font-size: 28px;
            margin: 50px 0 25px;
            position: relative;
            padding-bottom: 10px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: var(--primary-color);
        }

        .categories {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 50px;
        }

        .category-card {
            background-color: var(--white);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            text-align: center;
            cursor: pointer;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .category-img {
            height: 160px;
            background-color: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .category-img i {
            font-size: 60px;
            color: var(--primary-color);
            z-index: 1;
        }

        .category-img::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
        }

        .category-info {
            padding: 20px;
        }

        .category-info h3 {
            margin-bottom: 10px;
            font-size: 18px;
        }

        /* Products */
        .products {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 50px;
        }

        .product-card {
            background-color: var(--white);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .product-img {
            height: 200px;
            background-color: #f5f5f5;
            position: relative;
            overflow: hidden;
        }

        .product-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .product-card:hover .product-img img {
            transform: scale(1.05);
        }

        .product-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: var(--primary-color);
            color: var(--white);
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            z-index: 1;
        }

        .product-info {
            padding: 20px;
        }

        .product-info h3 {
            margin-bottom: 10px;
            font-size: 16px;
            font-weight: 600;
        }

        .product-price {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .current-price {
            font-size: 18px;
            font-weight: bold;
            color: var(--primary-color);
        }

        .old-price {
            font-size: 14px;
            color: var(--light-text);
            text-decoration: line-through;
        }

        .add-to-cart {
            width: 100%;
            background-color: var(--primary-color);
            color: var(--white);
            border: none;
            padding: 10px 0;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .add-to-cart:hover {
            background-color: var(--primary-dark);
        }

        /* Cart Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: flex-end;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .cart-modal {
            background-color: var(--white);
            width: 100%;
            max-width: 400px;
            height: 100vh;
            padding: 20px;
            overflow-y: auto;
            transform: translateX(100%);
            transition: transform 0.3s;
        }

        .modal-overlay.active .cart-modal {
            transform: translateX(0);
        }

        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .cart-header h3 {
            font-size: 20px;
        }

        .close-cart {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: var(--light-text);
        }

        .cart-items {
            margin-bottom: 20px;
        }

        .cart-item {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .cart-item-img {
            width: 80px;
            height: 80px;
            border-radius: 4px;
            overflow: hidden;
        }

        .cart-item-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cart-item-info {
            flex: 1;
        }

        .cart-item-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .cart-item-price {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 5px;
        }

        .cart-item-remove {
            color: var(--light-text);
            font-size: 12px;
            cursor: pointer;
        }

        .cart-total {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: right;
        }

        .cart-total span {
            color: var(--primary-color);
        }

        .checkout-btn {
            width: 100%;
            background-color: var(--primary-color);
            color: var(--white);
            border: none;
            padding: 12px 0;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .checkout-btn:hover {
            background-color: var(--primary-dark);
        }

        /* Footer */
        footer {
            background-color: #222;
            color: var(--white);
            padding: 60px 0 20px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .footer-column h3 {
            font-size: 18px;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-column h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 30px;
            height: 2px;
            background-color: var(--primary-color);
        }

        .footer-column ul {
            list-style: none;
        }

        .footer-column ul li {
            margin-bottom: 12px;
        }

        .footer-column ul li a {
            color: #bbb;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-column ul li a:hover {
            color: var(--primary-color);
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-links a {
            color: var(--white);
            background-color: #333;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .social-links a:hover {
            background-color: var(--primary-color);
            transform: translateY(-3px);
        }

        .copyright {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #444;
            color: #bbb;
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .hero h1 {
                font-size: 2rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
        }

        @media (max-width: 768px) {
            .header-top {
                flex-wrap: wrap;
            }
            
            .search-bar {
                order: 3;
                width: 100%;
                margin: 15px 0 0;
                max-width: 100%;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .nav-links {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background-color: var(--white);
                flex-direction: column;
                gap: 0;
                padding: 0;
                box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s;
            }
            
            .nav-links.active {
                max-height: 500px;
                padding: 15px 0;
            }
            
            .nav-links li {
                padding: 10px 20px;
                border-bottom: 1px solid var(--border-color);
            }
            
            .hero {
                padding: 40px 0;
            }
            
            .hero h1 {
                font-size: 1.8rem;
            }
            
            .modal-overlay {
                justify-content: center;
            }
            
            .cart-modal {
                max-width: 100%;
                height: 80vh;
                margin-top: 20vh;
                border-radius: 8px 8px 0 0;
            }
        }

        @media (max-width: 576px) {
            .hero-content {
                padding: 15px;
            }
            
            .hero h1 {
                font-size: 1.5rem;
            }
            
            .cta-button {
                padding: 10px 20px;
                font-size: 14px;
            }
            
            .section-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-top">
                <div class="logo">
                    <i class="fas fa-shopping-basket"></i>
                    <span>Sk Fruit</span>
                </div>
                
                <div class="search-bar">
                    <input type="text" placeholder="Search for products...">
                    <button><i class="fas fa-search"></i></button>
                </div>
                
                <div class="user-actions">
                    <a href="login.html">
                        <i class="far fa-user"></i>
                        <span>Account</span>
                    </a>
                    <a href="#">
                        <i class="far fa-heart"></i>
                        <span>Wishlist</span>
                    </a>
                    <a href="#" id="cart-btn" style="position: relative;">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Cart</span>
                        <span class="cart-count">0</span>
                    </a>
                </div>
            </div>
        </div>
        
        <nav>
            <div class="container">
                <button class="mobile-menu-btn" id="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </button>
                <ul class="nav-links" id="nav-links">
                    <li><a href="main.html">Home</a></li>
                    <li><a href="#">Shop</a></li>
                    <li><a href="#">Categories</a></li>
                    <li><a href="about.html">About</a></li>
                    <li><a href="contact-form/Contact_form.html">Contact</a></li>
                </ul>
            </div>
        </nav>
    </header>
    
    <section class="hero">
        <div class="hero-content">
            <h1>Fresh Groceries Delivered to Your Doorstep</h1>
            <p>Shop the freshest produce, dairy, and pantry staples with our convenient online service</p>
            <button class="cta-button">Shop Now</button>
        </div>
    </section>
    
    <main class="container">
        <h2 class="section-title">Shop by Category</h2>
        <div class="categories" id="categories">
            <!-- Categories will be loaded dynamically -->
        </div>
        
        <h2 class="section-title">Featured Products</h2>
        <div class="products" id="products">
            <!-- Products will be loaded dynamically -->
        </div>
    </main>
    
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>YellowCart</h3>
                    <p>Your one-stop shop for fresh groceries delivered to your doorstep. Quality products at affordable prices.</p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://web.whatsapp.com/"><i class="fab fa-whatsapp"></i></a>
                        <a href="https://www.instagram.com/"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.youtube.com/"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="footer-column">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="main.html">Home</a></li>
                        <li><a href="#">Shop</a></li>
                        <li><a href="about.html">About Us</a></li>
                        <li><a href="contact-form/Contact_form.html">Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Customer Service</h3>
                    <ul>
                        <li><a href="login.html">My Account</a></li>
                        <li><a href="#">Wishlist</a></li>
                        <li><a href="contact-form/Contact_form.html">Shipping Policy</a></li>
                        </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Contact Us</h3>
                    <ul>
                        <li><i class="fas fa-map-marker-alt"></i> Fruit's | Vegitable</li>
                        <li><i class="fas fa-phone"></i> (92)319-1811168 </li>
                        <li><i class="fas fa-envelope"></i> nainmalik47385@gmail.com</li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                &copy; 2025 YellowCart. haseebmalik827@gmail.com
            </div>
        </div>
    </footer>

    <!-- Cart Modal -->
    <div class="modal-overlay" id="cart-modal">
        <div class="cart-modal">
            <div class="cart-header">
                <h3>Your Cart</h3>
                <button class="close-cart" id="close-cart">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="cart-items" id="cart-items">
                <!-- Cart items will be added here -->
            </div>
            <div class="cart-total">
                Total: $<span id="cart-total">0.00</span>
            </div>
<a href="checkout.html">
    <button class="checkout-btn">Proceed to Checkout</button>
</a>
        </div>
    </div>

    <script>
        // Data for categories and products
        const categoriesData = [
            { id: 1, name: "Fruits & Vegetables", icon: "fas fa-apple-alt", items: "50+" },
            { id: 2, name: "Beverages", icon: "fas fa-wine-bottle", items: "30+" },
            { id: 3, name: "Bakery", icon: "fas fa-bread-slice", items: "25+" },
            { id: 4, name: "Frozen Foods", icon: "fas fa-ice-cream", items: "40+" },
            { id: 5, name: "Dairy & Eggs", icon: "fas fa-egg", items: "35+" },
            { id: 6, name: "Meat & Seafood", icon: "fas fa-drumstick-bite", items: "45+" },
            { id: 7, name: "Snacks", icon: "fas fa-cookie", items: "60+" },
            { id: 8, name: "Pantry Staples", icon: "fas fa-jar", items: "55+" }
        ];

        const productsData = [
            { id: 1, name: "Organic Bananas (1kg)", price: 2.99, oldPrice: 3.49, image: "https://images.unsplash.com/photo-1571771894821-ce9b6c11b08e", badge: "Sale" },
            { id: 2, name: "Fresh Strawberries (500g)", price: 4.99, image: "https://images.unsplash.com/photo-1464965911861-746a04b4bca6", badge: "New" },
            { id: 3, name: "Free Range Eggs (12pcs)", price: 3.49, image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcST6nN4R7SqtmCQXojJ2TUrLLQ_A85UqhZcnA&s" },
            { id: 4, name: "Whole Grain Bread (500g)", price: 2.49, image: "https://images.unsplash.com/photo-1509440159596-0249088772ff" },
            { id: 5, name: "Organic Spinach (200g)", price: 1.99, oldPrice: 2.49, image: "https://images.unsplash.com/photo-1576045057995-568f588f82fb", badge: "Sale" },
            { id: 6, name: "Almond Milk (1L)", price: 3.99, image: "https://images.unsplash.com/photo-1550583724-b2692b85b150" },
            { id: 7, name: "Greek Yogurt (500g)", price: 2.99, image: "https://www.shutterstock.com/image-photo/pictures-natural-fresh-fruit-juice-260nw-2335482853.jpg" },
            { id: 8, name: "Organic Tomatoes (500g)", price: 2.29, image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRdNM_c7SaCTjOwwJ2h4RflkHizQIO1cPDqha_6tMzDWz4KHiuOnDRwD3xL8q5GpnC3GCs&usqp=CAU" }
        ];

        // DOM Elements
        const categoriesContainer = document.getElementById('categories');
        const productsContainer = document.getElementById('products');
        const cartBtn = document.getElementById('cart-btn');
        const cartModal = document.getElementById('cart-modal');
        const closeCartBtn = document.getElementById('close-cart');
        const cartItemsContainer = document.getElementById('cart-items');
        const cartTotalElement = document.getElementById('cart-total');
        const cartCountElement = document.querySelector('.cart-count');
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const navLinks = document.getElementById('nav-links');

        // Cart state
        let cart = [];

        // Initialize the app
        document.addEventListener('DOMContentLoaded', () => {
            renderCategories();
            renderProducts();
            setupEventListeners();
        });

        // Render categories
        function renderCategories() {
            categoriesContainer.innerHTML = categoriesData.map(category => `
                <div class="category-card" data-id="${category.id}">
                    <div class="category-img">
                        <i class="${category.icon}"></i>
                    </div>
                    <div class="category-info">
                        <h3>${category.name}</h3>
                        <p>${category.items} items</p>
                    </div>
                </div>
            `).join('');
        }

        // Render products
        function renderProducts() {
            productsContainer.innerHTML = productsData.map(product => `
                <div class="product-card" data-id="${product.id}">
                    <div class="product-img">
                        <img src="${product.image}" alt="${product.name}">
                        ${product.badge ? `<span class="product-badge">${product.badge}</span>` : ''}
                    </div>
                    <div class="product-info">
                        <h3>${product.name}</h3>
                        <div class="product-price">
                            <span class="current-price">$${product.price.toFixed(2)}</span>
                            ${product.oldPrice ? `<span class="old-price">$${product.oldPrice.toFixed(2)}</span>` : ''}
                        </div>
                        <button class="add-to-cart">
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                    </div>
                </div>
            `).join('');
        }

// checkout button event listener

checkoutBtn.addEventListener('click', () => {
    if (cart.length === 0) {
        alert('Your cart is empty. Please add some items before checkout.');
        return;
    }
    // Save cart to localStorage before redirecting
    localStorage.setItem('cart', JSON.stringify(cart));
    // Redirect to checkout page
    window.location.href = 'checkout.html';
});



        // Setup event listeners
        function setupEventListeners() {
            // Mobile menu toggle
            mobileMenuBtn.addEventListener('click', () => {
                navLinks.classList.toggle('active');
            });

            // Cart toggle
            cartBtn.addEventListener('click', (e) => {
                e.preventDefault();
                cartModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            });

            closeCartBtn.addEventListener('click', () => {
                cartModal.classList.remove('active');
                document.body.style.overflow = 'auto';
            });

            // Close modal when clicking outside
            cartModal.addEventListener('click', (e) => {
                if (e.target === cartModal) {
                    cartModal.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            });

            // Add to cart buttons
            productsContainer.addEventListener('click', (e) => {
                if (e.target.classList.contains('add-to-cart') || e.target.closest('.add-to-cart')) {
                    const productCard = e.target.closest('.product-card');
                    const productId = parseInt(productCard.dataset.id);
                    addToCart(productId);
                }
            });

            // Remove item from cart
            cartItemsContainer.addEventListener('click', (e) => {
                if (e.target.classList.contains('cart-item-remove')) {
                    const cartItem = e.target.closest('.cart-item');
                    const productId = parseInt(cartItem.dataset.id);
                    removeFromCart(productId);
                }
            });
        }

        // Add product to cart
        function addToCart(productId) {
            const product = productsData.find(p => p.id === productId);
            if (!product) return;

            const existingItem = cart.find(item => item.id === productId);
            
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    image: product.image,
                    quantity: 1
                });
            }

            updateCart();
            showAddedToCartNotification(product.name);
        }

        // Remove product from cart
        function removeFromCart(productId) {
            cart = cart.filter(item => item.id !== productId);
            updateCart();
        }

        // Update cart UI
        function updateCart() {
            // Update cart items
            cartItemsContainer.innerHTML = cart.map(item => `
                <div class="cart-item" data-id="${item.id}">
                    <div class="cart-item-img">
                        <img src="${item.image}" alt="${item.name}">
                    </div>
                    <div class="cart-item-info">
                        <div class="cart-item-title">${item.name}</div>
                        <div class="cart-item-price">$${(item.price * item.quantity).toFixed(2)}</div>
                        <div>Quantity: ${item.quantity}</div>
                        <div class="cart-item-remove">Remove</div>
                    </div>
                </div>
            `).join('');

            // Update cart total
            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            cartTotalElement.textContent = total.toFixed(2);

            // Update cart count
            const itemCount = cart.reduce((count, item) => count + item.quantity, 0);
            cartCountElement.textContent = itemCount;

            // Save to localStorage
            localStorage.setItem('cart', JSON.stringify(cart));
        }

        // Show notification when item is added to cart
        function showAddedToCartNotification(productName) {
            const notification = document.createElement('div');
            notification.className = 'notification';
            notification.innerHTML = `
                <div style="position: fixed; bottom: 20px; right: 20px; background-color: var(--primary-color); color: white; padding: 15px; border-radius: 4px; box-shadow: 0 3px 10px rgba(0,0,0,0.2); z-index: 1000; animation: slideIn 0.3s ease-out;">
                    <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
                    ${productName} added to cart!
                </div>
            `;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease-in';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }

        // Load cart from localStorage
        function loadCart() {
            const savedCart = localStorage.getItem('cart');
            if (savedCart) {
                cart = JSON.parse(savedCart);
                updateCart();
            }
        }

        // Initialize cart
        loadCart();
    </script>
</body>
</html>