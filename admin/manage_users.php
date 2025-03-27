<?php
session_start();
include '../config/config.php';

// Redirect non-admins
if (!isset($_SESSION["user"]) || $_SESSION["account_type"] != 1) {
    header("Location: ../login.php");
    exit();
}

$message = "";

// Handle User Insert
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_user"])) {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"]; // Storing as plain text for now
    $account_type = $_POST["account_type"]; // 1 = Admin, 2 = User

    // Check for duplicate username or email
    $check_sql = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        $_SESSION['message'] = "<div id='message-box' class='error'>⚠️ Username or Email already exists.</div>";
    } else {
        // Insert user into database
        $sql = "INSERT INTO users (username, email, password, account_type) 
                VALUES ('$username', '$email', '$password', '$account_type')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = "<div id='message-box' class='success'>✅ User added successfully!</div>";
        } else {
            $_SESSION['message'] = "<div id='message-box' class='error'>⚠️ Error adding user.</div>";
        }
    }

    header("Location: manage_users.php");
    exit();
}


// Fetch Users (Ascending Order by ID)
$sql = "SELECT * FROM users ORDER BY id ASC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="styles/admin_style.css">
</head>
<body>

    <?php include 'admin_navbar.php'; ?>
    <?php
        // Display success/error message if available
        if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']); // Clear message after displaying
        }
    ?>


    <div class="admin-container">
        <a href="index.php" class="back-btn-pink">⬅</a>

        <h1>Manage Users</h1>

        <!-- Display Message -->
        <?php echo $message; ?>

        <!-- Insert New User Form -->
        <div class="form-container">
            <h2>Add New User</h2>
            <form action="manage_users.php" method="POST">
                <input type="text" name="username" placeholder="Username" required><br>
                <input type="email" name="email" placeholder="Email" required><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <label>Account Type:</label>
                <select name="account_type" required>
                    <option value="1">Admin</option>
                    <option value="2">User</option>
                </select><br>
                <button type="submit" name="add_user">Add User</button>
            </form>
        </div>

        <!-- User List Table -->
        <h2>Users List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>password</th>
                    <th>Account Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['username']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['password']}</td>
                            <td>" . ($row['account_type'] == 1 ? 'Admin' : 'User') . "</td>
                            <td>
                                <a href='manage_user_edit.php?id={$row['id']}' class='edit-btn'>Edit</a>
                                <a href='manage_user_delete.php?id={$row['id']}' class='delete-btn' '>Delete</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No users found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
<script>
    // Auto-hide success/error messages after 3 seconds
    setTimeout(function() {
        var messageBox = document.getElementById('message-box');
        if (messageBox) {
            messageBox.style.opacity = '0';
            setTimeout(function() {
                messageBox.style.display = 'none';
            }, 500); // Extra delay for smooth fade-out
        }
    }, 3000); // Hide after 3 seconds
</script>

</html>
