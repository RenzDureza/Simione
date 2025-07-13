
<?php
session_start();
include('../config/db.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $res = $conn->prepare("SELECT * FROM users WHERE email=?");
    $res->bind_param("s", $email);
    $res->execute();
    $user = $res->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: ../dashboard.php");
    } else {
        header("Location: ../error.php?msg=Invalid+username+or+password");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Kaisei+Tokumin&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1 >Simione</h1>
    
    <?php if (isset($error)): ?>
        <p style="color:red"><?= $error ?></p>
    <?php endif; ?>
    <h3>Login</h3>
    <p>Enter your email to login for this app</p>

    <div class="regis">
        <form method="POST">
        <input name="email" type="email" placeholder="Email@domain.com" required><br>
        <input name="password" type="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>
</div>    
</body>
</html>

