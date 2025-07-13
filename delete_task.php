
<?php
session_start();
include('config/db.php');

$task_id = $_POST['task_id'];
$list_id = $_POST['list_id'];
$user_id = $_SESSION['user_id'] ?? 0;

$stmt = $conn->prepare("
    SELECT tasks.id FROM tasks 
    JOIN lists ON tasks.list_id = lists.id
    WHERE tasks.id = ? AND (lists.owner_id = ? OR lists.id IN (
        SELECT list_id FROM collaborators WHERE user_id = ?
    ))
");
$stmt->bind_param("iii", $task_id, $user_id, $user_id);
$stmt->execute();
if ($stmt->get_result()->num_rows == 0) {
    die("Permission denied.");
}

$conn->query("DELETE FROM tasks WHERE id = $task_id");
header("Location: view_list.php?id=$list_id");
exit();