<?php
session_start();
include '../config/config.php';

// Redirect if user is not logged in
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["user"];
$message = "";

// Fetch user details
$sql = "SELECT * FROM users WHERE username='$username'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
} else {
    $message = "<div class='error'>⚠️ User not found.</div>";
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $new_username = trim($_POST["username"]);
    $new_email = trim($_POST["email"]);
    $new_password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validate email
    if (!empty($new_email) && !filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='error'>⚠️ Invalid email format.</div>";
    }
    // Validate password length & matching passwords
    elseif (!empty($new_password)) {
        if (strlen($new_password) < 6) {
            $message = "<div class='error'>⚠️ Password must be at least 6 characters long.</div>";
        } elseif ($new_password !== $confirm_password) {
            $message = "<div class='error'>⚠️ Passwords do not match.</div>";
        }
    }

    // Check if the new username already exists (if it's being changed)
    if (!empty($new_username) && $new_username !== $user['username']) {
        $check_username_sql = "SELECT * FROM users WHERE username='$new_username'";
        $username_result = $conn->query($check_username_sql);

        if ($username_result->num_rows > 0) {
            $message = "<div class='error'>⚠️ This username is already taken. Choose a different one.</div>";
        }
    }

    // Proceed with the update only if no errors
    if (empty($message)) {
        $updates = [];
        if (!empty($new_username) && $new_username !== $user['username']) {
            $updates[] = "username='$new_username'";
        }
        if (!empty($new_email)) {
            $updates[] = "email='$new_email'";
        }
        if (!empty($new_password)) {
            $updates[] = "password='$new_password'"; // Plain text for demo (Use hashing for real applications)
        }

        if (!empty($updates)) {
            $update_sql = "UPDATE users SET " . implode(", ", $updates) . " WHERE username='$username'";
            if ($conn->query($update_sql) === TRUE) {
                $message = "<div class='success'>✅ Account updated successfully.</div>";
                $_SESSION["user"] = !empty($new_username) ? $new_username : $_SESSION["user"]; // Update session username if changed
            } else {
                $message = "<div class='error'>⚠️ Error updating account.</div>";
            }
        } else {
            $message = "<div class='error'>⚠️ No changes made.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="stylesheet" href="main.css"> 
    <link rel="stylesheet" href="user_styles/my_account_style.css">
    <script>
        function enableEdit() {
            document.getElementById('username').disabled = false;
            document.getElementById('email').disabled = false;
            document.getElementById('password').disabled = false;
            document.getElementById('confirm_password').disabled = false;
            document.getElementById('update-btn').style.display = 'block';
            document.getElementById('modify-btn').style.display = 'none';
        }
    </script>
</head>
<body>

    <?php include 'user_includes/navbar.php'; ?>

    <div class="form-container">
        <a href="index.php" class="back-btn">⬅</a>
        <h2>My Account</h2>

        <?php echo $message; ?>

        <form action="my_account.php" method="POST">
            <label>Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled><br>

            <label>Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled><br>

            <label>New Password (optional):</label>
            <input type="password" id="password" name="password" placeholder="New Password (Min 6 characters)" disabled><br>

            <label>Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" disabled><br>

            <button type="button" id="modify-btn" onclick="enableEdit()">Modify</button>
            <button type="submit" name="update" id="update-btn" style="display: none;">Update Account</button>
        </form>
    </div>

    <?php include 'user_includes/footer.php'; ?>

</body>
<script>
    function toggleMenu() {
        document.querySelector(".nav-links").classList.toggle("active");
    }
</script>
</html>
