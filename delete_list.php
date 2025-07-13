<?php
session_start();
include('config/db.php');

$list_id = $_POST['list_id'];
$user_id = $_SESSION['user_id'];

// Only owner can delete
$stmt = $conn->prepare("SELECT * FROM lists WHERE id = ? AND owner_id = ?");
$stmt->bind_param("ii", $list_id, $user_id);
$stmt->execute();

if ($stmt->get_result()->num_rows == 0) {
    die("Not allowed.");
}

// Delete tasks, collaborators, and then list
$conn->query("DELETE FROM tasks WHERE list_id = $list_id");
$conn->query("DELETE FROM collaborators WHERE list_id = $list_id");
$conn->query("DELETE FROM lists WHERE id = $list_id");

header("Location: dashboard.php");
exit();