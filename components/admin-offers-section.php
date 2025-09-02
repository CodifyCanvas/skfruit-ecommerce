<div class="content-section">
    <div class="section-header">
        <h2>Offers Management</h2>
        <button class="btn btn-primary" id="open-offer-modal">
            <i class="fas fa-plus"></i> Add Offer
        </button>
    </div>
    <div class="table-responsive">
        <table>
            <thead class="table-heading">
                <tr>
                    <th>ID</th>
                    <th>Offer Name</th>
                    <th>Discount</th>
                    <th>Products</th>
                    <th>Valid Until</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="offer-table-body">
                <!-- Categories will be loaded here -->
            </tbody>
        </table>
    </div>
</div>

<!-- offer Modal -->
<div class="modal-overlay" id="offer-modal" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(0,0,0,0.4); justify-content:center; align-items:center; z-index:999;">
    <div class="modal" style="background-color: #fff; padding: 20px 25px; border-radius: 8px; width: 100%; max-width: 400px; box-shadow: 0 0 15px rgba(0,0,0,0.2); animation: modalSlideDown 0.3s ease;">
        <div class="modal-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 id="offer-modal-header">Add New offer</h2>
            <button class="close-btn" id="close-offer-modal" style="background:transparent; border:none; font-size:24px; color:#666; cursor:pointer;">&times;</button>
        </div>
        <form id="form-offer-modal" method="POST" enctype="multipart/form-data">
            <div class="modal-body" style="display: grid; grid-template-columns: 1fr; gap: 0.5rem;">

                <div hidden class="form-group" style="margin-bottom:15px;">
                    <label for="offer-name">Offer Id</label>
                    <input type="text" id="offer-id" name="offer_id" style="width:100%;  padding:8px 12px; font-size:14px; border:1px solid #ccc; border-radius:5px;" />
                </div>

                <div class="form-group" style="margin-bottom:15px;">
                    <label for="offer-name">Offer Name</label>
                    <input type="text" id="offer-name" placeholder="Laptop" name="offer_name" required style="width:100%; padding:8px 12px; font-size:14px; border:1px solid #ccc; border-radius:5px;" />
                </div>

                <div class="form-group" style="margin-bottom:15px;">
                    <label for="offer-products">Select Products</label>
                    <select id="offer-products" name="offer_products[]" required multiple>
                        <option value="">Select Product</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom:15px;">
                    <label for="offer-discount-per">Discount (%)</label>
                    <input type="number" min="1" step="1" id="offer-discount-per" name="offer_discount_per" required style="width:100%; padding:8px 12px; font-size:14px; border:1px solid #ccc; border-radius:5px;" />
                </div>

                <div class="form-group" style="margin-bottom:15px;">
                    <label for="offer-end-data">End Date</label>
                    <input type="date" id="offer-end-data" name="offer_end_date" required style="width:100%; padding:8px 12px; font-size:14px; border:1px solid #ccc; border-radius:5px;" />
                </div>

                <div class="form-group" style="margin-bottom:15px;">
                    <label for="offer-description">Description</label>
                    <input type="text" step="1" min="0" id="offer-description" name="offer_description" style="width:100%; padding:8px 12px; font-size:14px; border:1px solid #ccc; border-radius:5px;" />
                </div>

                <p id="offer-error-message" style="color:red; margin-bottom:10px; display:none;"></p>

            </div>
            <div class="modal-footer" style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="submit" class="modal-btn modal-btn-submit" id="offer-btn-submit" style="padding:8px 14px; border:none; border-radius:5px; font-size:14px; cursor:pointer; background-color:#28a745; color:#fff;">Add offer</button>
                <button type="button" class="modal-btn modal-btn-cancel" id="cancel-offer-modal-btn" style="padding:8px 14px; border:none; border-radius:5px; font-size:14px; cursor:pointer; background-color:#dc3545; color:#fff;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const openofferModalBtn = document.getElementById('open-offer-modal');
        const offerCloseModalBtn = document.getElementById('close-offer-modal');
        const offerCancelModalBtn = document.getElementById('cancel-offer-modal-btn');
        const offerModal = document.getElementById('offer-modal');

        const offerForm = document.getElementById('form-offer-modal');

        const offerNameInput = document.getElementById('offer-name');
        const offerProductsInput = document.getElementById('offer-products'); // it is a multi-select input that name is offer_products[]
        const offerDiscountInput = document.getElementById('offer-discount-per');
        const offerEndDateInput = document.getElementById('offer-end-data');
        const offerDescriptionInput = document.getElementById('offer-description');

        const offerErrorMessage = document.getElementById('offer-error-message');
        const offerSubmitBtn = document.getElementById('offer-btn-submit');
        const offerModalHeader = document.getElementById('offer-modal-header');
        const offerTableBody = document.getElementById('offer-table-body');

        // Show modal (display flex for centering)
        function showModal() {
            offerModal.style.display = 'flex';
        }
        // Hide modal
        function hideModal() {
            offerModal.style.display = 'none';
        }

        // Show Error Message
        function displayFormError(message) {
            offerErrorMessage.textContent = message;
            offerErrorMessage.style.display = 'block';
        }

        // Hide Error Message
        function clearFormError() {
            offerErrorMessage.textContent = '';
            offerErrorMessage.style.display = 'none';
        }

        // Generalized function for fetch requests
        function sendofferRequest(action, formData = null) {

            const baseURL = '<?= $baseURL ?>'; // Change as per your backend setup
            const url = `${baseURL}/controllers/offers-controller.php?action=${action}`;
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

        // Fetch offers and render
        function fetchoffers() {
            return sendofferRequest('fetch')
                .then(response => {
                    if (response && response.success) {

                        if (response.data) {
                            populateofferTable(response.data);
                        }

                        if (response.products) {
                            populateDropdownInput(response.products);
                        }
                    }
                })
                .catch(err => console.error('Error fetching categories:', err));
        }

        // Populate the Categories in Select Input
        function populateDropdownInput(options) {

            offerProductsInput.innerHTML = '';

            // Clear existing options (except the first one)
            // offerProductsInput.innerHTML = '<option value="">Select Category</option>';

            options.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.title;
                offerProductsInput.appendChild(option);
            });
        }

        // Populate data in table
        function populateofferTable(data) {
            offerTableBody.innerHTML = ''; // Clear existing

            data.forEach(offer => {
                const row = document.createElement('tr');

                const statusBadge = offer.status === 'active'? `<span style=" background-color: #0c8044ff; color: #fafffdff; font-size: 0.75rem; font-weight: 500; margin-right: 0.5rem; padding: 0.125rem 0.625rem; border-radius: 0.125rem; ">Active</span>` : `<span style=" background-color: #fd6363ff; color: #991b1b; font-size: 0.75rem; font-weight: 500; margin-right: 0.5rem; padding: 0.125rem 0.625rem; border-radius: 0.125rem; ">Expired</span>`;

                row.innerHTML = `
                <td>#${offer.id}</td>
                <td class="capitalize">${offer.offer_name}</td>
                <td>${offer.discount_percent}%</td>
                <td>${offer.product_ids.length}</td>
                <td>${offer.valid_until}</td>
                <td class="capitalize">${statusBadge}</td>
                <td class="action-btns">
                    <button id="edit-offer-${offer.id}" class="action-btn btn-primary" title="Edit offer">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button id="delete-offer-${offer.id}" class="action-btn btn-danger" title="Delete offer">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
                offerTableBody.appendChild(row);

                // Attach event listeners directly here
                document.getElementById(`edit-offer-${offer.id}`).addEventListener('click', function() {
                    openEditModal(offer.id, offer.product_ids, offer.offer_name, offer.description, offer.discount_percent, offer.valid_until);
                });

                document.getElementById(`delete-offer-${offer.id}`).addEventListener('click', function() {
                    deleteoffer(offer.id);
                });
            });
        }

        // Reset modal for adding new offer
        function resetofferModal() {
            offerForm.reset();
            clearFormError();

            offerNameInput.value = '';
            offerProductsInput.value = '';
            offerDiscountInput.value = 0;
            offerEndDateInput.value = 0;
            offerDescriptionInput.value = '';
            offerModalHeader.textContent = 'Add New offer';
            offerSubmitBtn.textContent = 'Add offer';

            // Clear the hidden offer id
            document.getElementById('offer-id').value = '';
        }

        // Open modal for editing offer
        function openEditModal(id, product_ids, offerName, description, discount, untilDate) {
            resetofferModal();

            offerModalHeader.textContent = 'Update offer';
            offerSubmitBtn.textContent = 'Update offer';

            offerNameInput.value = offerName;
            offerDiscountInput.value = discount;
            offerEndDateInput.value = untilDate;
            offerDescriptionInput.value = description;

            // Set selected options for multiple select (offer_products)
    let productIds = product_ids ?? [];

    Array.from(offerProductsInput.options).forEach(option => {
        option.selected = productIds.includes(parseInt(option.value));
    });

            document.getElementById('offer-id').value = id;

            showModal();
        }

        // Delete offer function
        function deleteoffer(id) {
            if (!confirm('Are you sure you want to delete this offer?')) return;

            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('id', id); // Backend expects 'id' here for delete action

            sendofferRequest('delete', formData)
                .then(() => {
                    alert('offer deleted successfully!');
                    fetchoffers();
                })
                .catch(() => {
                    alert('Failed to delete offer.');
                });
        }

        // Event Listeners
        openofferModalBtn.addEventListener('click', () => {
            resetofferModal();
            fetchoffers().then(() => {
                showModal(); // Show modal after categories are updated
            });
        });

        offerCloseModalBtn.addEventListener('click', () => {
            hideModal();
        });

        offerCancelModalBtn.addEventListener('click', () => {
            hideModal();
        });

        offerForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(offerForm);
            const offerId = formData.get('offer_id')?.trim();
            const isEdit = offerId !== '';
            if (isEdit) {
                formData.set('id', offerId);
            }

            const action = isEdit ? 'update' : 'create';

            sendofferRequest(action, formData)
                .then(() => {
                    // Only success alert here
                    alert(`${ isEdit ? 'Offer updated successfully!' : 'Offer saved successfully!' }`);
                    offerForm.reset();
                    fetchoffers();
                    hideModal();
                })
                .catch(err => {
                    displayFormError(err.message || 'Something went wrong');
                });
        });

        // Initial fetch of categories on page load
        fetchoffers();
    });
</script>