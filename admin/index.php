<?php
session_start();
include '../config/config.php';

// Redirect non-admins
if (!isset($_SESSION["user"]) || $_SESSION["account_type"] != 1) {
    header("Location: ../login.php");
    exit();
}

// Fetch Total Users (Exclude Admins)
$user_query = "SELECT COUNT(*) AS total_users FROM users WHERE account_type = 2";
$user_result = $conn->query($user_query);
$total_users = $user_result->fetch_assoc()['total_users'];

// Fetch Total Products
$product_query = "SELECT COUNT(*) AS total_products FROM products";
$product_result = $conn->query($product_query);
$total_products = $product_result->fetch_assoc()['total_products'];

// Fetch Total Orders
$order_query = "SELECT COUNT(*) AS total_orders FROM orders";
$order_result = $conn->query($order_query);
$total_orders = $order_result->fetch_assoc()['total_orders'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles/admin_style.css">
</head>
<body>

    <?php include 'admin_navbar.php'; ?>

    <div class="admin-container">
        <h1>Welcome, Admin</h1>
        <p>Manage users, products, and orders from this dashboard.</p>

        <!-- Inline Analytics Section -->
        <div class="admin-analytics">
            <div class="analytics-box">
                <h2><?php echo $total_users; ?></h2>
                <p>Total Users</p>
            </div>
            <div class="analytics-box">
                <h2><?php echo $total_products; ?></h2>
                <p>Total Products</p>
            </div>
            <div class="analytics-box">
                <h2><?php echo $total_orders; ?></h2>
                <p>Total Orders</p>
            </div>
        </div>

        <!-- Management Buttons -->
        <div class="admin-actions">
            <a href="manage_users.php" class="btn">Manage Users</a>
            <a href="manage_product.php" class="btn">Manage Products</a>
            <a href="manage_orders.php" class="btn">Manage Orders</a>
            <a href="../logout.php" class="btn logout">Logout</a>
        </div>
    </div>

</body>
</html>
