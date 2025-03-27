<?php
session_start();
include '../config/config.php';

// Ensure user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Check if order ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = $_GET['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Delete</title>
    <link rel="stylesheet" href="user_styles/order_details_style.css">
</head>
<body>

    <?php include 'user_includes/navbar.php'; ?>

    <div class="confirmation-container">
        <h2>🗑️ Confirm Deletion</h2>
        <p>Are you sure you want to delete this order?</p>

        <form action="delete_order.php" method="POST">
            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
            <button type="submit" class="btn delete-btn">Yes, Delete</button>
            <a href="order_details.php?id=<?php echo $order_id; ?>" class="btn cancel-btn">Cancel</a>
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
