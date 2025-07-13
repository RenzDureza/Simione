
<?php
session_start();
include('config/db.php');

// If already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Prevent duplicate email
    $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $error = "Email already registered!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);
        $stmt->execute();

        // Redirect to login
        header("Location: auth/login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Kaisei+Tokumin&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Simione</title>
</head>
<body>
    <h1 >Simione</h1>
    
    <?php if (isset($error)): ?>
        <p style="color:red"><?= $error ?></p>
    <?php endif; ?>
    <h3>Create an account</h3>
    <p>Enter your email to sign up for this app</p>
    <div class="regis">
        <form method="POST">
            <input name="name" placeholder="Name" required><br>
            <input name="email" type="email" placeholder="email@domain.com" required><br>
            <input name="password" type="password" placeholder="Password" required><br>
            <button type="submit">Register</button>
        </form>
        <hr class="solid">
        <a href="auth/login.php">
            <button>Login</button>
        </a>
    </div>
</body>
</html>