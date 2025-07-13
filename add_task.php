<?php
session_start();
include('config/db.php');

if (!isset($_GET['id'])) {
    die("Missing list ID.");
}

$list_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// 🔒 Check access to the list
$stmt = $conn->prepare("
    SELECT * FROM lists 
    WHERE id = ? AND (owner_id = ? OR id IN (
        SELECT list_id FROM collaborators WHERE user_id = ?
    ))
");
$stmt->bind_param("iii", $list_id, $user_id, $user_id);
$stmt->execute();
$list = $stmt->get_result()->fetch_assoc();

if (!$list) {
    die("Access denied or list not found.");
}

// 📝 Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $due_date = $_POST['due_date'];
    $due_time = $_POST['due_time'];

    $due_datetime = $due_date . ' ' . $due_time;


    $insert = $conn->prepare("INSERT INTO tasks (list_id, title, due_date) VALUES (?, ?, ?)");
    $insert->bind_param("iss", $list_id, $title, $due_datetime);
    $insert->execute();

    header("Location: view_list.php?id=$list_id");
    exit();
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
    <title>Simione - Add Task</title>
</head>
<body>
    <h1>Simione</h1>
    <div class="add_task_card">
        <h3>Add New Task</h3>
        <hr class="solid">
        <div class="add_task">
            <form method="POST">
                <div class="top_input"><input type="text" name="title" placeholder="Task title" required></div>
                <div class="middle_input">
                    <input type="time" name="due_time" required><br>
                    <input type="date" name="due_date" required><br><br>
                </div>
                <button type="submit">Add Task</button>
            </form>
        </div>
    </div>
    <br>
    <div class="back_btn"><a href="view_list.php?id=<?= $list_id ?>"><button>Back</button></a></div>
</body>
</html>
