<?php

// return $pdo (PDO object)
include 'db_connect.php'; 

session_start();

$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validate input
    if ($email === '' || $password === '') {
        $message = 'Please fill in all fields.';
        if ($isAjax) {
            echo $message;
        } else {
            $error = $message;
        }
        exit;
    }

    try {
        // Prepare secure SQL query
        $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // If user found and password matches
        if ($user && $user['password'] === $password) {

            // Store in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            if ($isAjax) {
                echo 'success:' . $user['role'];
            } else {
                header("Location: " . $baseURL . ($user['role'] === 'admin' ? "admin.php" : "index.php"));
            }
            exit;
        } else {
            $message = 'Invalid email or password.';
            if ($isAjax) {
                echo $message;
            } else {
                $error = $message;
            }
            exit;
        }
    } catch (PDOException $e) {
        // Optional: log error or return custom message
        if ($isAjax) {
            echo 'Server error';
        } else {
            $error = 'Server error';
        }
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Fruit's | Vegitable</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color:rgb(34, 108, 34);
            --primary-dark: rgb(2, 93, 2);
            --text-color: black;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        
        body {
            background-color: #028a29;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .login-container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        
        .logo {
            margin-bottom: 30px;
        }
        
        .logo i {
            font-size: 40px;
            color: var(--primary-color);
        }
        
        .logo h1 {
            color: var(--text-color);
            margin-top: 10px;
        }
        
        .login-form .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        
        .login-form label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-color);
            font-weight: bold;
        }
        
        .login-form input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .login-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 0;
            width: 100%;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        
        .login-btn:hover {
            background-color: var(--primary-dark);
        }
        
        .error-message {
            color: red;
            margin-top: 15px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <i class="fas fa-shopping-basket"></i>
            <h1>Admin Login</h1>
        </div>
        
        <form method="POST" action="" class="login-form" id="loginForm">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter password" required>
            </div>
            
            <button type="submit" class="login-btn">Login</button>
            
            <div id="error-message" class="error-message"></div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('loginForm');
    const errorMessage = document.getElementById('error-message');

    if (!form) {
        console.error('Login form not found.');
        return;
    }

    form.addEventListener('submit', async (e) => {
        // Stop form from reloading the page
        e.preventDefault(); 

        // Clear previous error messages
        errorMessage.style.display = 'none';
        errorMessage.textContent = '';

        const formData = new FormData(form);

        // Make an AJAX POST request to this same page
        try {
            const response = await fetch('', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const result = await response.text();

            if (result.startsWith('success:')) {
                // Extract role from server response
                const role = result.split(':')[1]?.trim();

                // Base URL (should match PHP $baseURL)
                const baseURL = '<?= $baseURL ?>';

                // Redirect based on role
                window.location.href = baseURL + (role === 'admin' ? '/admin.php' : '');
            } else {
                // Show error from server
                errorMessage.textContent = result;
                errorMessage.style.display = 'block';
            }

        } catch (error) {
            console.error('AJAX error:', error);
            errorMessage.textContent = 'Something went wrong. Please try again.';
            errorMessage.style.display = 'block';
        }
    });
});
</script>

</body>
</html>