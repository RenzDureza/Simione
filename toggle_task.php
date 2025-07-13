
<?php
session_start();
include('config/db.php');

$task_id = $_POST['task_id'];
$is_done = isset($_POST['is_done']) ? 1 : 0;
$user_id = $_SESSION['user_id'] ?? 0;

// Make sure the user owns or is collaborating on the list containing this task
$query = "
SELECT tasks.list_id FROM tasks 
JOIN lists ON tasks.list_id = lists.id
WHERE tasks.id = ? 
AND (lists.owner_id = ? OR lists.id IN (
    SELECT list_id FROM collaborators WHERE user_id = ?
))";

$stmt = $conn->prepare($query);
$stmt->bind_param("iii", $task_id, $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Permission denied.");
}

$list_id = $row['list_id'];

// Update the task status
$update = $conn->prepare("UPDATE tasks SET is_done = ? WHERE id = ?");
$update->bind_param("ii", $is_done, $task_id);
$update->execute();

header("Location: view_list.php?id=$list_id");
exit();