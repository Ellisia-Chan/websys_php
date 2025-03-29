<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar">
    <div class="logo">
        <img src="../images/logo.png" alt="Logo">
    </div>
    
    <!-- Hamburger Icon -->
    <div class="hamburger" onclick="toggleMenu()">
        &#9776; <!-- Unicode for hamburger icon -->
    </div>

    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <?php if (isset($_SESSION["user"])): ?>
            <li><a href="my_account.php">Account</a></li>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="orders.php">Orders</a></li>
            <li><a href="../logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="../login.php">Login</a></li>
            <li><a href="../registration.php">Sign Up</a></li>
        <?php endif; ?>
    </ul>
</nav>
