<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="navbar">
    <a href="index.php" class="logo">
        <img src="../images/logo.png" alt="Admin Logo">
    </a>

    <!-- Hamburger Button -->
    <div class="hamburger" onclick="toggleMenu()">☰</div>

    <ul class="nav-links">
        <li><a href="index.php">Dashboard</a></li>
        <li><a href="manage_users.php">Users</a></li>
        <li><a href="manage_product.php">Products</a></li>
        <li><a href="manage_orders.php">Orders</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</div>

<script>
function toggleMenu() {
    document.querySelector(".nav-links").classList.toggle("active");
}
</script>
