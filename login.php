<?php
include 'config/config.php';
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    // Secure query using prepared statements
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Validate password (plain text)
        if ($password === $user["password"]) {
            // Store user info in session
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user"] = $user["username"];
            $_SESSION["account_type"] = $user["account_type"];

            // Redirect based on account type
            if ($user["account_type"] == 1) {
                header("Location: admin"); // Admin Redirect
            } else {
                header("Location: user"); // User Redirect
            }
            exit();
        } else {
            $message = "<div class='message-container error'>⚠️ Incorrect username or password.</div>";
        }
    } else {
        $message = "<div class='message-container error'>⚠️ Incorrect username or password.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>

    <?php include 'includes/navbar.php'; ?>

    <div class="form-container">
        <!-- Back Button at the Top -->
        <a href="index.php" class="back-btn-pink">⬅</a>

        <h2>Login</h2>

        <!-- Display Message -->
        <?php echo $message; ?>

        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="registration.php">Sign up here</a></p>
    </div>


    <?php include 'includes/footer.php'; ?>

</body>

<script>
    function toggleMenu() {
        document.querySelector(".nav-links").classList.toggle("active");
    }
</script>

</html>
