
<?php
session_start();
include('config/db.php');
$user_id = $_SESSION['user_id'] ?? 0;

$query = "
SELECT * FROM lists 
WHERE owner_id = ? 
OR id IN (SELECT list_id FROM collaborators WHERE user_id = ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$lists = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Kaisei+Tokumin&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Simione</h1>
    
    <div class="task-card">
        <h3>Pages</h3>
        <hr>
        <?php while ($row = $lists->fetch_assoc()): ?>
            <div class="view_list">
                <a href="view_list.php?id=<?= $row['id'] ?>"><button type="button" class="list-title"><?= htmlspecialchars($row['name']) ?></button></a>
                <form method="POST" action="delete_list.php" class="delete-form" style="display:inline;">
                    <input type="hidden" name="list_id" value="<?= $row['id'] ?>">
                    <button type="submit" class="delete-list" onclick="return confirm('Delete this list?')">&#x26D2;</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
        
    <div class="bottom-buttons">
        <a href="auth/logout.php">
            <button type="button">Logout</button>
        </a>
        <a href="create_list.php">
           <button type="button">Create List</button> 
        </a>
    </div>
</body>
</html>