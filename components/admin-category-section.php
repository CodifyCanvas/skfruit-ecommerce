<div class="content-section">
    <div class="section-header">
        <h2>Category Management</h2>
        <button class="btn btn-primary" id="open-category-modal">
            <i class="fas fa-plus"></i> Add Category
        </button>
    </div>
    <div class="table-responsive">
        <table>
            <thead class="table-heading">
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="category-table-body">
                <!-- Categories will be loaded here -->
            </tbody>
        </table>
    </div>
</div>

<!-- Category Modal -->
<div class="modal-overlay" id="category-modal" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(0,0,0,0.4); justify-content:center; align-items:center; z-index:999;">
    <div class="modal" style="background-color: #fff; padding: 20px 25px; border-radius: 8px; width: 100%; max-width: 400px; box-shadow: 0 0 15px rgba(0,0,0,0.2); animation: modalSlideDown 0.3s ease;">
        <div class="modal-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 id="category-modal-header">Add New Category</h2>
            <button class="close-btn" id="close-category-modal" style="background:transparent; border:none; font-size:24px; color:#666; cursor:pointer;">&times;</button>
        </div>
        <form id="form-category-modal" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <div hidden class="form-group" style="margin-bottom:15px;">
                    <label for="category-name">Category Id</label>
                    <input type="text" id="category-id" name="category_id" style="width:100%;  padding:8px 12px; font-size:14px; border:1px solid #ccc; border-radius:5px;" />
                </div>

                <!-- Image Preview (shown only in edit mode) -->
                <div class="form-group" id="category-image-preview-container" style="display:none; margin-bottom:15px;">
                    <label for="category-image-preview">Category Image</label>
                    <img id="category-image-preview" src="" alt="Category Image" style="max-width:100%; max-height:200px; object-fit:cover;" />
                </div>

                <div class="form-group" style="margin-bottom:15px;">
                    <label for="category-image">Category Image</label>
                    <input type="file" id="category-image" name="category_image" accept="image/*" />
                </div>

                <div class="form-group" style="margin-bottom:15px;">
                    <label for="category-name">Category Name</label>
                    <input type="text" id="category-name" name="category_name" required style="width:100%; padding:8px 12px; font-size:14px; border:1px solid #ccc; border-radius:5px;" />
                </div>

                <p id="category-error-message" style="color:red; margin-bottom:10px; display:none;"></p>
            </div>
            <div class="modal-footer" style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="submit" class="modal-btn modal-btn-submit" id="category-btn-submit" style="padding:8px 14px; border:none; border-radius:5px; font-size:14px; cursor:pointer; background-color:#28a745; color:#fff;">Add Category</button>
                <button type="button" class="modal-btn modal-btn-cancel" id="cancel-category-modal-btn" style="padding:8px 14px; border:none; border-radius:5px; font-size:14px; cursor:pointer; background-color:#dc3545; color:#fff;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const openCategoryModalBtn = document.getElementById('open-category-modal');
        const closeCategoryModalBtn = document.getElementById('close-category-modal');
        const cancelCategoryModalBtn = document.getElementById('cancel-category-modal-btn');
        const categoryModal = document.getElementById('category-modal');
        const categoryForm = document.getElementById('form-category-modal');
        const categoryNameInput = document.getElementById('category-name');
        const categoryImageInput = document.getElementById('category-image');
        const submitButton = document.getElementById('category-btn-submit');
        const categoryModalHeader = document.getElementById('category-modal-header');
        const categoryImagePreviewContainer = document.getElementById('category-image-preview-container');
        const categoryImagePreview = document.getElementById('category-image-preview');
        const categoryTableBody = document.getElementById('category-table-body');
        const CategoryErrorMessage = document.getElementById('category-error-message');

        // Show modal (display flex for centering)
        function showModal() {
            categoryModal.style.display = 'flex';
        }
        // Hide modal
        function hideModal() {
            categoryModal.style.display = 'none';
        }

        // Show Error Message
        function displayFormError(message) {
            CategoryErrorMessage.textContent = message;
            CategoryErrorMessage.style.display = 'block';
        }

        // Hide Error Message
        function clearFormError() {
            CategoryErrorMessage.textContent = '';
            CategoryErrorMessage.style.display = 'none';
        }

        // Generalized function for fetch requests
        function sendCategoryRequest(action, formData = null) {
            const baseURL = '<?= $baseURL ?>'; // Change as per your backend setup
            const url = `${baseURL}/controllers/category-controller.php?action=${action}`;
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

        // Fetch categories and render
        function fetchCategories() {
            sendCategoryRequest('fetch')
                .then(data => {
                    if (data && data.data) {
                        populateCategoryTable(data.data);
                    }
                })
                .catch(err => console.error('Error fetching categories:', err));
        }

        // Populate categories in table
        function populateCategoryTable(categories) {
            categoryTableBody.innerHTML = ''; // Clear existing

            categories.forEach(category => {
                const row = document.createElement('tr');

                row.innerHTML = `
                <td>#${category.id}</td>
                <td class="capitalize">${category.category.charAt(0).toUpperCase() + category.category.slice(1)}</td>
                <td class="action-btns">
                    <button id="edit-category-${category.id}" class="action-btn btn-primary" title="Edit Category">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button id="delete-category-${category.id}" class="action-btn btn-danger" title="Delete Category">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
                categoryTableBody.appendChild(row);

                // Attach event listeners directly here
                document.getElementById(`edit-category-${category.id}`).addEventListener('click', function() {
                    openEditModal(category.id, category.category, category.image_path);
                });

                document.getElementById(`delete-category-${category.id}`).addEventListener('click', function() {
                    deleteCategory(category.id);
                });
            });
        }

        // Reset modal for adding new category
        function resetCategoryModal() {
            categoryForm.reset();
            clearFormError();
            
            categoryNameInput.value = '';
            categoryImageInput.value = '';
            categoryModalHeader.textContent = 'Add New Category';
            submitButton.textContent = 'Add Category';
            categoryImagePreviewContainer.style.display = 'none';
            categoryImagePreview.src = '';

            // Clear the hidden category id
            document.getElementById('category-id').value = '';
        }

        // Open modal for editing category
        function openEditModal(id, name, imagePath) {
            resetCategoryModal();

            categoryModalHeader.textContent = 'Update Category';
            submitButton.textContent = 'Update Category';

            categoryNameInput.value = name;
            document.getElementById('category-id').value = id;

            if (imagePath) {
                categoryImagePreviewContainer.style.display = 'block';
                categoryImagePreview.src = imagePath;
            } else {
                categoryImagePreviewContainer.style.display = 'none';
                categoryImagePreview.src = '';
            }

            showModal();
        }

        // Delete category function
        function deleteCategory(id) {
            if (!confirm('Are you sure you want to delete this category?')) return;

            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('id', id); // Backend expects 'id' here for delete action

            sendCategoryRequest('delete', formData)
                .then(() => {
                    alert('Category deleted successfully!');
                    fetchCategories();
                })
                .catch(() => {
                    alert('Failed to delete category.');
                });
        }

        // Event Listeners
        openCategoryModalBtn.addEventListener('click', () => {
            resetCategoryModal();
            showModal();
        });

        closeCategoryModalBtn.addEventListener('click', () => {
            hideModal();
        });

        cancelCategoryModalBtn.addEventListener('click', () => {
            hideModal();
        });

        categoryForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(categoryForm);
            const categoryId = document.getElementById('category-id').value.trim();
            const isEdit = categoryId !== '';

            if (isEdit) {
                formData.set('id', categoryId); 
            }

            const action = isEdit ? 'update' : 'create';

            sendCategoryRequest(action, formData)
                .then(() => {
                    // Only success alert here
                    alert(`${isEdit ? 'Category updated successfully!' : 'Category saved successfully!'}`);
                    categoryForm.reset();
                    fetchCategories();
                    hideModal();
                })
                .catch(err => {
                    displayFormError(err.message || 'Something went wrong');
                });
        });

        // Initial fetch of categories on page load
        fetchCategories();
    });
</script>