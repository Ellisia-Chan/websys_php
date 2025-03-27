<?php 
// Only start a session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="navbar">
    <!-- Logo -->
    <a href="index.php" class="logo">
        <img src="images/logo.png" alt="TreadSpot Logo">
    </a>

    <!-- Navigation Links -->
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <?php if (isset($_SESSION["user"])): ?>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="registration.php">Sign Up</a></li>
        <?php endif; ?>
    </ul>
</div>
