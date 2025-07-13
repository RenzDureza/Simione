
<?php
session_start();
include('config/db.php');

$list_id = $_GET['id'];
$user_id = $_SESSION['user_id'] ?? 0;

// Check if user owns the list
$stmt = $conn->prepare("SELECT * FROM lists WHERE id=? AND owner_id=?");
$stmt->bind_param("ii", $list_id, $user_id);
$stmt->execute();
$list = $stmt->get_result()->fetch_assoc();

if (!$list) {
    die("");
    header("Location: error.php?msg=You+are+not+allowed+to+invite+others+to+this+list");
    exit();
}

// Generate a public token if it doesn't exist yet
if (!$list['public_token']) {
    $token = bin2hex(random_bytes(16));
    $update = $conn->prepare("UPDATE lists SET public_token=? WHERE id=?");
    $update->bind_param("si", $token, $list_id);
    $update->execute();
    $list['public_token'] = $token;
}

$token = $list['public_token'];
$link = "http://localhost/todo/join.php?token=$token"; // Change this path if your folder name is different
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
    <title>Simione - Invite</title>
</head>
<body>
    
    <h1><a href="dashboard.php" style="text-decoration: none; color: inherit;">Simione</a></h1>

    <div class="add_task_card">
        <h3>Share this link</h3>
        <hr class="solid">

        <div class="copy-section">
            <input type="text" id="inviteLink" value="<?= htmlspecialchars($link) ?>" readonly>
            <button class="copy-btn" onclick="copyLink()">Copy Invite Link</button>
        </div>
    </div>

    <div class="back_btn">
        <a href="view_list.php?id=<?= $list_id ?>"><button>Back</button></a>
    </div>

    <script>
        function copyLink() {
            const input = document.getElementById("inviteLink");
            input.select();
            input.setSelectionRange(0, 99999); // For mobile
            document.execCommand("copy");
            alert("✅ Invite link copied to clipboard!");
        }
    </script>
</body>
</html>
