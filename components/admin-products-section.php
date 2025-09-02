<div class="content-section">
    <div class="section-header">
        <h2>product Management</h2>
        <button class="btn btn-primary" id="open-product-modal">
            <i class="fas fa-plus"></i> Add product
        </button>
    </div>
    <div class="table-responsive">
        <table>
            <thead class="table-heading">
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="product-table-body">
                <!-- Categories will be loaded here -->
            </tbody>
        </table>
    </div>
</div>

<!-- product Modal -->
<div class="modal-overlay" id="product-modal" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(0,0,0,0.4); justify-content:center; align-items:center; z-index:999;">
    <div class="modal" style="background-color: #fff; padding: 20px 25px; border-radius: 8px; width: 100%; max-width: 600px; box-shadow: 0 0 15px rgba(0,0,0,0.2); animation: modalSlideDown 0.3s ease;">
        <div class="modal-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 id="product-modal-header">Add New product</h2>
            <button class="close-btn" id="close-product-modal" style="background:transparent; border:none; font-size:24px; color:#666; cursor:pointer;">&times;</button>
        </div>
        <form id="form-product-modal" method="POST" enctype="multipart/form-data">
            <div class="modal-body" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">

                <div hidden class="form-group" style="margin-bottom:15px;">
                    <label for="product-name">product Id</label>
                    <input type="text" id="product-id" name="product_id" style="width:100%;  padding:8px 12px; font-size:14px; border:1px solid #ccc; border-radius:5px;" />
                </div>

                <!-- Image Preview (shown only in edit mode) -->
                <div class="form-group" id="product-image-preview-container" style="display:none; grid-column: span 2; margin-bottom:15px;">
                    <label for="product-image-preview">Product Image</label>
                    <img id="product-image-preview" src="" alt="product Image" style="max-width: 100%; max-height: 100px; object-fit: contain;" />
                </div>

                <div class="form-group" style="margin-bottom:15px;">
                    <label for="product-image">Product Image</label>
                    <input type="file" id="product-image" name="product_image" accept="image/*" />
                </div>

                <div class="form-group" style="margin-bottom:15px;">
                    <label for="product-name">Product Name</label>
                    <input type="text" id="product-name" placeholder="Laptop" name="product_name" required style="width:100%; padding:8px 12px; font-size:14px; border:1px solid #ccc; border-radius:5px;" />
                </div>

                <div class="form-group" style="margin-bottom:15px;">
                    <label for="product-category">Product Category</label>
                    <select id="product-category" name="product_category" required>
                        <option value="">Select Category</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom:15px;">
                    <label for="product-price">Product Price</label>
                    <input type="number" step="0.01" min="1" id="product-price" name="product_price" required style="width:100%; padding:8px 12px; font-size:14px; border:1px solid #ccc; border-radius:5px;" />
                </div>

                <div class="form-group" style="margin-bottom:15px;">
                    <label for="product-stock">Product Stock</label>
                    <input type="number" step="1" min="0" id="product-stock" name="product_stock" required style="width:100%; padding:8px 12px; font-size:14px; border:1px solid #ccc; border-radius:5px;" />
                </div>

                <div class="form-group" style="margin-bottom:15px;">
                    <label for="product-description">Product Description</label>
                    <Input type="text" id="product-description" name="product_description" style="width:100%; padding:8px 12px; font-size:14px; border:1px solid #ccc; border-radius:5px;" />
                </div>

                <p id="product-error-message" style="color:red; margin-bottom:10px; display:none;"></p>

            </div>
            <div class="modal-footer" style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="submit" class="modal-btn modal-btn-submit" id="product-btn-submit" style="padding:8px 14px; border:none; border-radius:5px; font-size:14px; cursor:pointer; background-color:#28a745; color:#fff;">Add product</button>
                <button type="button" class="modal-btn modal-btn-cancel" id="cancel-product-modal-btn" style="padding:8px 14px; border:none; border-radius:5px; font-size:14px; cursor:pointer; background-color:#dc3545; color:#fff;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const openproductModalBtn = document.getElementById('open-product-modal');
        const closeproductModalBtn = document.getElementById('close-product-modal');
        const cancelproductModalBtn = document.getElementById('cancel-product-modal-btn');
        const productModal = document.getElementById('product-modal');

        const productForm = document.getElementById('form-product-modal');

        const productImageInput = document.getElementById('product-image');
        const productTitleInput = document.getElementById('product-name');
        const productCategoryInput = document.getElementById('product-category');
        const productPriceInput = document.getElementById('product-price');
        const productStockInput = document.getElementById('product-stock');
        const productDescriptionInput = document.getElementById('product-description');

        const productErrorMessage = document.getElementById('product-error-message');
        const submitButton = document.getElementById('product-btn-submit');
        const productModalHeader = document.getElementById('product-modal-header');
        const productImagePreviewContainer = document.getElementById('product-image-preview-container');
        const productImagePreview = document.getElementById('product-image-preview');
        const productTableBody = document.getElementById('product-table-body');

        // Show modal (display flex for centering)
        function showModal() {
            productModal.style.display = 'flex';
        }
        // Hide modal
        function hideModal() {
            productModal.style.display = 'none';
        }

        // Show Error Message
        function displayFormError(message) {
            productErrorMessage.textContent = message;
            productErrorMessage.style.display = 'block';
        }

        // Hide Error Message
        function clearFormError() {
            productErrorMessage.textContent = '';
            productErrorMessage.style.display = 'none';
        }

        // Generalized function for fetch requests
        function sendproductRequest(action, formData = null) {

            const baseURL = '<?= $baseURL ?>'; // Change as per your backend setup
            const url = `${baseURL}/controllers/product-controller.php?action=${action}`;
            const options = formData ? {
                method: 'POST',
                body: formData
            } : {
                method: 'GET'
            };

            return fetch(url, options)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        return data;
                    } else {
                        throw new Error(data.message || 'Request failed');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    throw error; // <-- re-throw to propagate error to caller
                });
        }

        // Fetch Products and render
        function fetchProducts() {
            return sendproductRequest('fetch')
                .then(response => {
                    if (response && response.success) {

                        if (response.data) {
                            populateproductTable(response.data);
                        }

                        if (response.categories) {
                            populateCategoryDropdown(response.categories);
                        }
                    }
                })
                .catch(err => console.error('Error fetching categories:', err));
        }

        // Populate the Categories in Select Input
        function populateCategoryDropdown(categories) {
            const categorySelect = document.getElementById('product-category');

            categorySelect.innerHTML = '';

            // Clear existing options (except the first one)
            // categorySelect.innerHTML = '<option value="">Select Category</option>';

            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.category;
                categorySelect.appendChild(option);
            });
        }

        // Populate data in table
        function populateproductTable(data) {
            productTableBody.innerHTML = ''; // Clear existing

            data.forEach(product => {
                const row = document.createElement('tr');

                row.innerHTML = `
                <td>#${product.id}</td>
                <td class="capitalize">${product.title}</td>
                <td>${product.price}</td>
                <td class="capitalize">${product.stock}</td>
                <td class="action-btns">
                    <button id="edit-product-${product.id}" class="action-btn btn-primary" title="Edit product">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button id="delete-product-${product.id}" class="action-btn btn-danger" title="Delete product">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
                productTableBody.appendChild(row);

                // Attach event listeners directly here
                document.getElementById(`edit-product-${product.id}`).addEventListener('click', function() {
                    openEditModal(product.id, product.category_id, product.title, product.description, product.stock, product.price, product.image_path);
                });

                document.getElementById(`delete-product-${product.id}`).addEventListener('click', function() {
                    deleteproduct(product.id);
                });
            });
        }

        // Reset modal for adding new product
        function resetproductModal() {
            productForm.reset();
            clearFormError();

            productImageInput.value = '';
            productTitleInput.value = '';
            productCategoryInput.value = '';
            productPriceInput.value = 0;
            productStockInput.value = 0;
            productDescriptionInput.value = '';
            productModalHeader.textContent = 'Add New product';
            submitButton.textContent = 'Add product';
            productImagePreviewContainer.style.display = 'none';
            productImagePreview.src = '';

            // Clear the hidden product id
            document.getElementById('product-id').value = '';
        }

        // Open modal for editing product
        function openEditModal(id, category_id, title, description, stock, price, imagePath) {
            resetproductModal();

            productModalHeader.textContent = 'Update product';
            submitButton.textContent = 'Update product';

            productTitleInput.value = title;
            productCategoryInput.value = category_id;
            productPriceInput.value = price;
            productStockInput.value = stock;
            productDescriptionInput.value = description;


            document.getElementById('product-id').value = id;

            if (imagePath) {
                productImagePreviewContainer.style.display = 'block';
                productImagePreview.src = imagePath;
            } else {
                productImagePreviewContainer.style.display = 'none';
                productImagePreview.src = '';
            }

            showModal();
        }

        // Delete product function
        function deleteproduct(id) {
            if (!confirm('Are you sure you want to delete this product?')) return;

            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('id', id); // Backend expects 'id' here for delete action

            sendproductRequest('delete', formData)
                .then(() => {
                    alert('Product deleted successfully!');
                    fetchProducts();
                })
                .catch(() => {
                    alert('Failed to delete product.');
                });
        }

        // Event Listeners
        openproductModalBtn.addEventListener('click', () => {
            resetproductModal();
            fetchProducts().then(() => {
                showModal(); // Show modal after categories are updated
            });
        });

        closeproductModalBtn.addEventListener('click', () => {
            hideModal();
        });

        cancelproductModalBtn.addEventListener('click', () => {
            hideModal();
        });

        productForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(productForm);
            const productId = document.getElementById('product-id').value.trim();
            const isEdit = productId !== '';

            if (isEdit) {
                formData.set('id', productId);
            }

            const action = isEdit ? 'update' : 'create';

            sendproductRequest(action, formData)
                .then(() => {
                    // Only success alert here
                    alert(`${ isEdit ? 'Product updated successfully!' : 'Product saved successfully!' }`);
                    productForm.reset();
                    fetchProducts();
                    hideModal();
                })
                .catch(err => {
                    displayFormError(err.message || 'Something went wrong');
                });
        });

        // Initial fetch of categories on page load
        fetchProducts();
    });
</script>