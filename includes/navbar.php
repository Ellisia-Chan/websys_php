<?php 
// Only start a session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar">
    <div class="logo">
        <img src="images/logo.png" alt="Logo">
    </div>
    
    <!-- Hamburger Icon -->
    <div class="hamburger" onclick="toggleMenu()">
        &#9776; <!-- Unicode for hamburger icon -->
    </div>

    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="registration.php">Sign Up</a></li>
    </ul>
</nav>