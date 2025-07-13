<?php
$message = $_GET['msg'] ?? 'An unexpected error occurred.';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .error-box {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            border: 1px solid #f44336;
            border-radius: 10px;
            background-color: #ffe6e6;
            text-align: center;
        }

        .error-box h1 {
            color: #f44336;
            margin-bottom: 10px;
        }

        .error-box p {
            color: #555;
            margin-bottom: 20px;
        }

        .error-box a {
            text-decoration: none;
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            border-radius: 6px;
        }

        .error-box a:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <div class="error-box">
        <h1>Error ⚠️</h1>
        <p><?= htmlspecialchars($message) ?></p>
        <a href="index.php">Go back</a>
    </div>
</body>
</html>