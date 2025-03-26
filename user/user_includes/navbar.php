<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="navbar">
    <a href="index.php" class="logo">
        <img src="logo.png" alt="ShoeStore Logo">
    </a>

    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <?php if (isset($_SESSION["user"])): ?>
            <li><a href="my_account.php">My Account</a></li>
            <li><a href="orders.php">Orders</a></li>
            <li><a href="../logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="../login.php">Login</a></li>
            <li><a href="../registration.php">Sign Up</a></li>
        <?php endif; ?>
    </ul>
</div>
