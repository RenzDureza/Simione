
<?php
session_start();
include('config/db.php');
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $owner_id = $_SESSION['user_id'];
    $token = uniqid();

    $stmt = $conn->prepare("INSERT INTO lists (name, owner_id, public_token) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $name, $owner_id, $token);
    $stmt->execute();

    header("Location: dashboard.php");
}
?>
    
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Kaisei+Tokumin&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Simione - Create List</title>
</head>
<body>
    <h1>Simione</h1>
    <div class="add_task_card">
        <h3>Create List</h3>
        <hr class="solid"> 
        <div class="add_task">
            <form method="POST">
                <div class="top_input"><input name="name" placeholder="List name" required><br></div>
                <div class="middle_input"><button type="submit">Create List</button></div>
            </form>
            <a href="dashboard.php?id=<?= $user_id ?>"><button>Back</button></a>
        </div>
    </div>
</body>
</html>

