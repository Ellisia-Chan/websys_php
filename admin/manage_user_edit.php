<?php
session_start();
include '../config/config.php';

// Redirect if not admin
if (!isset($_SESSION["user"]) || $_SESSION["account_type"] != 1) {
    header("Location: ../login.php");
    exit();
}

// Check if user ID is provided
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    header("Location: manage_users.php");
    exit();
}

$user_id = intval($_GET["id"]);
$message = "";

// Fetch user details securely
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
} else {
    header("Location: manage_users.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_user"])) {
    $new_username = trim($_POST["username"]);
    $new_email = trim($_POST["email"]);
    $account_type = intval($_POST["account_type"]);
    $new_password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validate required fields
    if (empty($new_username) || empty($new_email) || empty($account_type)) {
        $message = "<div class='error'>⚠️ All fields are required except password.</div>";
    }
    // Validate email format
    elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='error'>⚠️ Invalid email format.</div>";
    }
    // Validate password only if provided
    elseif (!empty($new_password)) {
        if (strlen($new_password) < 6) {
            $message = "<div class='error'>⚠️ Password must be at least 6 characters long.</div>";
        } elseif ($new_password !== $confirm_password) {
            $message = "<div class='error'>⚠️ Passwords do not match.</div>";
        }
    }

    // Check for duplicate username or email (excluding the current user)
    if (empty($message)) {
        $check_sql = "SELECT * FROM users WHERE (username = ? OR email = ?) AND id != ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("ssi", $new_username, $new_email, $user_id);
        $stmt->execute();
        $check_result = $stmt->get_result();

        if ($check_result->num_rows > 0) {
            $message = "<div class='error'>⚠️ Username or email is already taken.</div>";
        } else {
            // Prepare the update query dynamically
            $updates = "username=?, email=?, account_type=?";
            $params = [$new_username, $new_email, $account_type];
            $types = "ssi";

            if (!empty($new_password)) {
                $updates .= ", password=?";
                $params[] = $new_password; // Storing as plain text for now (use hashing in real apps)
                $types .= "s";
            }

            // Execute the update query securely
            $update_sql = "UPDATE users SET $updates WHERE id=?";
            $params[] = $user_id;
            $types .= "i";

            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                $message = "<div class='success'>✅ User updated successfully!</div>";
                // Refresh user data
                $user['username'] = $new_username;
                $user['email'] = $new_email;
                $user['account_type'] = $account_type;
            } else {
                $message = "<div class='error'>⚠️ Error updating user.</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="styles/admin_style.css">
</head>
<body>

    <?php include 'admin_navbar.php'; ?>

    <div class="admin-container">
        <h1>Edit User</h1>

        <!-- Display Message -->
        <?php echo $message; ?>

        <!-- Edit User Form -->
        <div class="form-container">
            <form action="manage_user_edit.php?id=<?php echo $user_id; ?>" method="POST">
                <label>Username:</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br>

                <label>Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

                <label>New Password (Optional):</label>
                <input type="password" name="password" placeholder="New Password (Min 6 characters)"><br>

                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" placeholder="Retype Password"><br>

                <label>Account Type:</label>
                <select name="account_type">
                    <option value="1" <?php echo ($user['account_type'] == 1) ? 'selected' : ''; ?>>Admin</option>
                    <option value="2" <?php echo ($user['account_type'] == 2) ? 'selected' : ''; ?>>User</option>
                </select><br>

                <button type="submit" name="update_user">Update User</button>
            </form>
            <a href="manage_users.php" class="btn back-btn">Back to Users</a>
        </div>
    </div>

</body>
</html>
