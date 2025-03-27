<?php
include 'config/config.php';

// Initialize a message variable
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"]; // Store as plain text
    $account_type = 2; // Default account type (User)

    // Basic form validation
    if (empty($username) || empty($email) || empty($password)) {
        $message = "<div class='error'>⚠️ All fields are required.</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='error'>⚠️ Invalid email format.</div>";
    } else {
        // Check if the username or email already exists
        $check_sql = "SELECT * FROM users WHERE username='$username' OR email='$email'";
        $result = $conn->query($check_sql);

        if ($result->num_rows > 0) {
            $message = "<div class='error'>⚠️ Username or email already exists.</div>";
        } else {
            // Insert user with a plain text password
            $sql = "INSERT INTO users (username, email, password, account_type) 
                    VALUES ('$username', '$email', '$password', '$account_type')";

            if ($conn->query($sql) === TRUE) {
                $message = "<div class='success'>✅ Registration successful! <a href='login.php'>Login here</a></div>";
            } else {
                $message = "<div class='error'>⚠️ Something went wrong. Please try again.</div>";
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
    <title>Register</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>

    <?php include 'includes/navbar.php'; ?>

    <div class="form-container">
    <!-- Back Button at the Top Left Inside the Form -->
    <a href="index.php" class="back-btn">⬅</a>

    <h2>Sign Up</h2>

    <!-- Display Message -->
    <?php echo $message; ?>

    <form action="registration.php" method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>


    <?php include 'includes/footer.php'; ?>

</body>
</html>
