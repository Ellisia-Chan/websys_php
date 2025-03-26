<?php
session_start();
include '../config/config.php';

// Redirect non-admins
if (!isset($_SESSION["user"]) || $_SESSION["account_type"] != 1) {
    header("Location: ../login.php");
    exit();
}

// Check if user ID is provided
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    $_SESSION['message'] = "<div class='error'>⚠️ Invalid user ID.</div>";
    header("Location: manage_users.php");
    exit();
}

$user_id = intval($_GET["id"]);

// Fetch user details for confirmation
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
} else {
    $_SESSION['message'] = "<div class='error'>⚠️ User not found.</div>";
    header("Location: manage_users.php");
    exit();
}

// Handle deletion confirmation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_user"])) {
    $confirmed_username = trim($_POST["confirm_username"]);

    if ($confirmed_username !== $user['username']) {
        $_SESSION['message'] = "<div class='error'>⚠️ Username does not match. Deletion canceled.</div>";
        header("Location: manage_users.php");
        exit();
    } else {
        // Execute secure delete query
        $delete_sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "<div class='success'>✅ User deleted successfully!</div>";
        } else {
            $_SESSION['message'] = "<div class='error'>⚠️ Error deleting user.</div>";
        }

        header("Location: manage_users.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
    <link rel="stylesheet" href="styles/admin_style.css">
</head>
<body>

    <?php include 'admin_navbar.php'; ?>

    <div class="admin-container">
        <h1>Delete User</h1>

        <div class="form-container">
            <h2>Confirm Deletion</h2>
            <p>To confirm, type the username below and click "Delete".</p>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>

            <form action="manage_user_delete.php?id=<?php echo $user_id; ?>" method="POST">
                <label>Username to Delete:</label>
                <input type="text" name="confirm_username" placeholder="Type username to confirm" required><br>
                
                <button type="submit" name="delete_user" class="delete-btn">Delete User</button>
            </form>
            <a href="manage_users.php" class="btn back-btn">Cancel</a>
        </div>
    </div>

</body>
</html>
