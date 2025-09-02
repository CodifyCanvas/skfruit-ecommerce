<h2 class="section-title">Shop by Category</h2>
<div class="categories" id="categories">
    <!-- Categories will be loaded dynamically -->
</div>

<script>
    const baseURL = '<?= $baseURL ?>';

    async function fetchData(url) {
        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error fetching data:', error);
            return null;
        }
    }

    const categoriesContainer = document.getElementById('categories');

    async function renderCategories() {
        const result = await fetchData(`${baseURL}/controllers/public/category.php`);

        if (!result || !Array.isArray(result.data)) {
            console.error("categoriesData is not a valid array:", result);
            return;
        }

        const categories = result.data;


        categoriesContainer.innerHTML = categories.map(category => `
    <div class="category-card" data-id="${category.id}">
        <div class="category-img">
            ${category.image ? `<img src="${baseURL}/${category.image}" alt="${category.name}" style="height: 100%; width: 100%;">` : `<i class="${category.icon}"></i>`}
        </div>
        <div class="category-info">
            <h3>${category.name}</h3>
            <p>${category.product_count > 0 ? `${category.product_count}+ items` : `0 item`}</p>
        </div>
    </div>
`).join('');


        // Add click listeners after rendering
        document.querySelectorAll('.category-card').forEach(card => {
            card.addEventListener('click', () => {
                const id = card.getAttribute('data-id');
                window.location.href = `${baseURL}/category.php?id=${id}`;
            });
        });
    }

    document.addEventListener('DOMContentLoaded', renderCategories);
</script>