<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle AJAX logout request (must be BEFORE any output)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_unset();
    session_destroy();

    // Prevent any whitespace or output before this
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit;
}

// Auth check (redirect to login if not logged in)
if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit;
}
?>

<div class="header">
    <h1 id="page-title">Loading...</h1>
    <div class="user-info" style="display: flex; justify-content: center; align-items: center; gap: 6px;">
        <span style="text-transform: capitalize; margin-bottom: 6px;">
            <?= htmlspecialchars($_SESSION['name']) ?>
        </span>
        <button id="session-logout" title="Logout">
            <span class="fas fa-sign-out-alt" style="font-size: 20px; margin-bottom: 5px; color: red; cursor: pointer;"></span>
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Set dynamic page title
    const path = window.location.pathname;
    const pageTitle = document.getElementById('page-title');

    if (path.endsWith('orders.php')) {
        pageTitle.textContent = 'Orders';
    } else if (path.endsWith('admin.php')) {
        pageTitle.textContent = 'Dashboard';
    } else {
        pageTitle.textContent = '';
    }

    // Logout functionality
    const logoutBtn = document.getElementById('session-logout');
    logoutBtn.addEventListener('click', async () => {
        if (!confirm('Are you sure you want to logout?')) return;

        try {
            const formData = new FormData();
            formData.append('logout', '1');

            const response = await fetch(window.location.href, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                window.location.href = 'login.php';
            } else {
                alert('Logout failed.');
            }
        } catch (error) {
            console.error('Logout error:', error);
        } finally {
        window.location.href = 'login.php';
        }
    });
});
</script>
