<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error - Something went wrong</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff3f3;
            color: #a94442;
            padding: 20px;
        }
        .error-box {
            border: 1px solid #ebccd1;
            background-color: #f2dede;
            padding: 20px;
            border-radius: 5px;
        }
        h2 {
            margin-top: 0;
        }
        .btn-back {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #d9534f;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-back:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
    <div class="error-box">
        <h2>Error Occurred</h2>
        <p><strong>Title:</strong> <?= htmlspecialchars($_GET['title'] ?? 'Unknown Error') ?></p>
        <p><strong>Error:</strong> <?= htmlspecialchars($_GET['message'] ?? 'No error message provided.') ?></p>
        <p><strong>On Line:</strong> <?= htmlspecialchars($_GET['line'] ?? 'Unknown') ?></p>
        <p><strong>File:</strong> <?= htmlspecialchars($_GET['file'] ?? 'Not specified') ?></p>
        <p><strong>Time:</strong> <?= date("Y-m-d H:i:s") ?></p>

        <!-- Go Back Button -->
        <button class="btn-back" onclick="window.history.back();">Go Back</button>
    </div>
</body>
</html>
