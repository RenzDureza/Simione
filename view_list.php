
<?php
session_start();
include('config/db.php');

$list_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Verify user access to the list
$stmt = $conn->prepare("SELECT * FROM lists WHERE id=? AND (owner_id=? OR id IN (SELECT list_id FROM collaborators WHERE user_id=?))");
$stmt->bind_param("iii", $list_id, $user_id, $user_id);
$stmt->execute();
$list = $stmt->get_result()->fetch_assoc();

if (!$list) {
    header("Location: error.php?msg=You+don%27t+have+access+to+this+list");
    exit();
}

// ✅ Load tasks
$tasks = $conn->query("SELECT * FROM tasks WHERE list_id = $list_id");
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
    <title>Simione</title>
</head>
<body>
    <h1><a href="dashboard.php" style="text-decoration: none; color: inherit;">Simione</a></h1>

    <div class="task-card">
        <h3><?= htmlspecialchars($list['name']) ?></h3>
        <hr>

        <ul class="task-list">
        <?php while ($task = $tasks->fetch_assoc()): ?>
            <?php
            $due_str = '';
            $due_style = '';
            $is_overdue = false;
            $is_tomorrow = false;

            if ($task['due_date']) {
                $now = strtotime('today');
                $due = strtotime(date('Y-m-d', strtotime($task['due_date'])));
                $due_str = date('m/d/Y', strtotime($task['due_date']));

                if (!$task['is_done']) {
                    if ($due < $now) {
                        $due_style = 'color: orange;';
                    } elseif ($due == strtotime('+1 day', $now)) {
                        $due_str = 'Tomorrow';
                        $due_style = 'color: red;';
                    }
                }
            }
            ?>
            
            <li class="task-item">
                <form method="POST" action="toggle_task.php" class="task-form">
                    <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                    <input type="checkbox" name="is_done" onchange="this.form.submit()" <?= $task['is_done'] ? 'checked' : '' ?>>
                </form>

                <div class="task-content <?= $task['is_done'] ? 'task-done' : '' ?>">
                    <span class="task-title"><?= htmlspecialchars($task['title']) ?></span>

                    <span class="due-date" style="<?= $due_style ?>">
                        <?= $due_str ?>
                    </span>

                    <form method="POST" action="delete_task.php" class="delete-form">
                        <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                        <input type="hidden" name="list_id" value="<?= $list_id ?>">
                        <button type="submit" class="delete-btn" onclick="return confirm('Delete this task?')">&#x26D2;</button>
                    </form>
                </div>
            </li>
        <?php endwhile; ?>
        </ul>
    </div>
       
    <div class="bottom-buttons">
        <a href="add_task.php?id=<?= $list_id ?>">
            <button type="button">Add</button>
        </a>

        <?php if ($list['owner_id'] == $user_id): ?>
            <form action="invite.php" method="GET">
                <input type="hidden" name="id" value="<?= $list_id ?>">
                <button type="submit">Collab</button>
            </form>
        <?php else: ?>
            <form method="POST" action="leave_list.php" onsubmit="return confirm('Are you sure you want to leave this list?')">
                <input type="hidden" name="list_id" value="<?= $list_id ?>">
                <button type="submit" class="leave-btn">Leave</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>