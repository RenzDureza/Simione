<?php
session_start();
include('config/db.php');

$token = $_GET['token'] ?? '';
$user_id = $_SESSION['user_id'] ?? null;

if (!$token || !$user_id) {
    header("Location: error.php?msg=Missing+token+or+not+logged+in");
    exit();
}

// Get the list with the token
$stmt = $conn->prepare("SELECT id FROM lists WHERE public_token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
$list = $result->fetch_assoc();

if (!$list) die("Invalid token.");

$list_id = $list['id'];

// Check if already a collaborator
$check = $conn->prepare("SELECT * FROM collaborators WHERE list_id = ? AND user_id = ?");
$check->bind_param("ii", $list_id, $user_id);
$check->execute();
$existing = $check->get_result()->fetch_assoc();

if (!$existing) {
    $insert = $conn->prepare("INSERT INTO collaborators (list_id, user_id) VALUES (?, ?)");
    $insert->bind_param("ii", $list_id, $user_id);
    $insert->execute();
}

header("Location: view_list.php?id=$list_id");
exit();
