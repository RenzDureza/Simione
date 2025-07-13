<?php
session_start();
include('config/db.php');

$list_id = $_POST['list_id'] ?? 0;
$user_id = $_SESSION['user_id'] ?? 0;

// Make sure this user is a collaborator (and not the owner)
$stmt = $conn->prepare("SELECT owner_id FROM lists WHERE id = ?");
$stmt->bind_param("i", $list_id);
$stmt->execute();
$result = $stmt->get_result();
$list = $result->fetch_assoc();

if (!$list || $list['owner_id'] == $user_id) {
    die("You can't leave your own list or the list doesn't exist.");
}

// Delete from collaborators
$delete = $conn->prepare("DELETE FROM collaborators WHERE list_id = ? AND user_id = ?");
$delete->bind_param("ii", $list_id, $user_id);
$delete->execute();

// Redirect to dashboard
header("Location: dashboard.php");
exit();