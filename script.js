// Mobile Menu Toggle
const navbarLinks = document.querySelectorAll(".nav-menu .nav-link");
const menuOpenButton = document.querySelector("#menu-open-button");
const menuCloseButton = document.querySelector("#menu-close-button");
menuOpenButton.addEventListener("click", () => {
  document.body.classList.toggle("show-mobile-menu");
});
menuCloseButton.addEventListener("click", () => menuOpenButton.click());
navbarLinks.forEach((link) => {
  link.addEventListener("click", () => menuOpenButton.click());
});

// Product Data
const products = [
  {
    id: 1,
    title: "Ethiopian Yirgacheffe",
    origin: "Ethiopia",
    description: "Bright and floral with notes of bergamot and lemon. Light roast.",
    price: 14.99,
    category: "single-origin",
    image: "images/coffee-1.jpg"
  },
  {
    id: 2,
    title: "Colombian Supremo",
    origin: "Colombia",
    description: "Medium body with caramel sweetness and nutty undertones. Medium roast.",
    price: 12.99,
    category: "single-origin",
    image: "images/coffee-2.jpg"
  },
  {
    id: 3,
    title: "Breakfast Blend",
    origin: "Multiple Origins",
    description: "Our signature blend with balanced flavor and smooth finish. Medium-dark roast.",
    price: 11.99,
    category: "blends",
    image: "images/coffee-3.jpg"
  },
  {
    id: 4,
    title: "Espresso Roast",
    origin: "Latin America",
    description: "Dark and rich with chocolate notes. Perfect for espresso. Dark roast.",
    price: 13.99,
    category: "blends",
    image: "images/coffee-4.jpg"
  },
  {
    id: 5,
    title: "Swiss Water Decaf",
    origin: "Colombia",
    description: "Chemical-free decaf process preserves flavor. Medium roast.",
    price: 15.99,
    category: "decaf",
    image: "images/coffee-5.jpg"
  },
  {
    id: 6,
    title: "French Press",
    origin: "Equipment",
    description: "Stainless steel French press with double filter. 34oz capacity.",
    price: 24.99,
    category: "equipment",
    image: "images/french-press.jpg"
  },
  {
    id: 7,
    title: "Pour Over Kit",
    origin: "Equipment",
    description: "Complete pour over set with ceramic dripper and carafe.",
    price: 29.99,
    category: "equipment",
    image: "images/pour-over.jpg"
  },
  {
    id: 8,
    title: "Coffee Grinder",
    origin: "Equipment",
    description: "Adjustable burr grinder for consistent grind size.",
    price: 49.99,
    category: "equipment",
    image: "images/grinder.jpg"
  }
];

// Shopping Cart
let cart = JSON.parse(localStorage.getItem('cart')) || [];
const cartCount = document.querySelector('.cart-count');
const cartModal = document.getElementById('cart-modal');
const cartItemsContainer = document.querySelector('.cart-items');
const cartTotal = document.querySelector('.total-price');
const closeCartBtn = document.querySelector('.close-cart');
const clearCartBtn = document.querySelector('.clear-cart');
const checkoutBtn = document.querySelector('.checkout-btn');

// Display Products
function displayProducts(filter = 'all') {
  const productList = document.querySelector('.product-list');
  productList.innerHTML = '';
  
  const filteredProducts = filter === 'all' 
    ? products 
    : products.filter(product => product.category === filter);
  
  filteredProducts.forEach(product => {
    const productItem = document.createElement('li');
    productItem.className = 'product-item';
    productItem.dataset.category = product.category;
    
    productItem.innerHTML = `
      <img src="${product.image}" alt="${product.title}" class="product-image">
      <div class="product-info">
        <h3 class="product-title">${product.title}</h3>
        <p class="product-origin">${product.origin}</p>
        <p class="product-description">${product.description}</p>
        <div class="product-footer">
          <span class="product-price">€${product.price.toFixed(2)}</span>
          <button class="add-to-cart" data-id="${product.id}">Add to Cart</button>
        </div>
      </div>
    `;
    
    productList.appendChild(productItem);
  });
  
  // Add event listeners to "Add to Cart" buttons
  document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', addToCart);
  });
}

// Filter Products
document.querySelectorAll('.filter-btn').forEach(button => {
  button.addEventListener('click', () => {
    document.querySelector('.filter-btn.active').classList.remove('active');
    button.classList.add('active');
    displayProducts(button.dataset.filter);
  });
});

// Add to Cart
function addToCart(e) {
  const productId = parseInt(e.target.dataset.id);
  const product = products.find(p => p.id === productId);
  
  const existingItem = cart.find(item => item.id === productId);
  
  if (existingItem) {
    existingItem.quantity += 1;
  } else {
    cart.push({
      ...product,
      quantity: 1
    });
  }
  
  updateCart();
  showCartNotification(product.title);
}

// Show Cart Notification
function showCartNotification(productName) {
  const notification = document.createElement('div');
  notification.className = 'cart-notification';
  notification.textContent = `${productName} added to cart!`;
  document.body.appendChild(notification);
  
  setTimeout(() => {
    notification.classList.add('show');
  }, 10);
  
  setTimeout(() => {
    notification.classList.remove('show');
    setTimeout(() => {
      document.body.removeChild(notification);
    }, 300);
  }, 3000);
}

// Update Cart
function updateCart() {
  localStorage.setItem('cart', JSON.stringify(cart));
  cartCount.textContent = cart.reduce((total, item) => total + item.quantity, 0);
  
  // Update cart modal
  cartItemsContainer.innerHTML = '';
  
  if (cart.length === 0) {
    cartItemsContainer.innerHTML = '<p>Your cart is empty</p>';
    document.querySelector('.cart-actions').style.display = 'none';
  } else {
    document.querySelector('.cart-actions').style.display = 'flex';
    
    cart.forEach(item => {
      const cartItem = document.createElement('div');
      cartItem.className = 'cart-item';
      
      cartItem.innerHTML = `
        <img src="${item.image}" alt="${item.title}" class="cart-item-image">
        <div class="cart-item-details">
          <h4 class="cart-item-title">${item.title}</h4>
          <p class="cart-item-price">€${item.price.toFixed(2)}</p>
          <div class="cart-item-quantity">
            <button class="quantity-btn minus" data-id="${item.id}">-</button>
            <span>${item.quantity}</span>
            <button class="quantity-btn plus" data-id="${item.id}">+</button>
          </div>
          <p class="remove-item" data-id="${item.id}">Remove</p>
        </div>
      `;
      
      cartItemsContainer.appendChild(cartItem);
    });
    
    // Add event listeners to quantity buttons
    document.querySelectorAll('.quantity-btn.minus').forEach(button => {
      button.addEventListener('click', decreaseQuantity);
    });
    
    document.querySelectorAll('.quantity-btn.plus').forEach(button => {
      button.addEventListener('click', increaseQuantity);
    });
    
    document.querySelectorAll('.remove-item').forEach(button => {
      button.addEventListener('click', removeItem);
    });
  }
  
  // Update total
  const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
  cartTotal.textContent = `€${total.toFixed(2)}`;
}

// Cart Quantity Functions
function increaseQuantity(e) {
  const productId = parseInt(e.target.dataset.id);
  const item = cart.find(item => item.id === productId);
  item.quantity += 1;
  updateCart();
}

function decreaseQuantity(e) {
  const productId = parseInt(e.target.dataset.id);
  const item = cart.find(item => item.id === productId);
  
  if (item.quantity > 1) {
    item.quantity -= 1;
  } else {
    cart = cart.filter(item => item.id !== productId);
  }
  
  updateCart();
}

function removeItem(e) {
  const productId = parseInt(e.target.dataset.id);
  cart = cart.filter(item => item.id !== productId);
  updateCart();
}

// Cart Modal Functions
function openCart() {
  cartModal.style.display = 'flex';
  document.body.style.overflow = 'hidden';
}

function closeCart() {
  cartModal.style.display = 'none';
  document.body.style.overflow = 'auto';
}

// Event Listeners
document.querySelector('.cart-icon').addEventListener('click', (e) => {
  e.preventDefault();
  openCart();
});

closeCartBtn.addEventListener('click', closeCart);
clearCartBtn.addEventListener('click', () => {
  cart = [];
  updateCart();
});
checkoutBtn.addEventListener('click', () => {
  alert('Checkout functionality would be implemented here!');
  cart = [];
  updateCart();
  closeCart();
});

// Close modal when clicking outside
window.addEventListener('click', (e) => {
  if (e.target === cartModal) {
    closeCart();
  }
});

// Initialize Swiper
let swiper = new Swiper(".slider-wrapper", {
  loop: true,
  grabCursor: true,
  spaceBetween: 25,
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
    dynamicBullets: true,
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  breakpoints: {
    0: {
      slidesPerView: 1,
    },
    768: {
      slidesPerView: 2,
    },
    1024: {
      slidesPerView: 3,
    },
  },
});

// Initialize Page
document.addEventListener('DOMContentLoaded', () => {
  displayProducts();
  updateCart();
  
  // Add cart notification styles
  const style = document.createElement('style');
  style.textContent = `
    .cart-notification {
      position: fixed;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%) translateY(100%);
      background: var(--secondary-color);
      color: var(--primary-color);
      padding: 12px 24px;
      border-radius: var(--border-radius-m);
      font-weight: var(--font-weight-medium);
      box-shadow: var(--shadow-m);
      transition: transform 0.3s ease;
      z-index: 1000;
    }
    .cart-notification.show {
      transform: translateX(-50%) translateY(0);
    }
  `;
  document.head.appendChild(style);
});