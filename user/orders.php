<?php
session_start();
include '../config/config.php';

// Ensure user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch user orders
$sql = "SELECT orders.id, orders.quantity, orders.total_price, orders.payment_method, orders.status,
               products.name AS product_name, products.image 
        FROM orders 
        JOIN products ON orders.product_id = products.id 
        WHERE orders.user_id = ? 
        ORDER BY orders.id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - TreadSpot</title>
    <link rel="stylesheet" href="user_styles/orders_style.css"> <!-- Separate CSS file -->
</head>
<body>

    <?php include 'user_includes/navbar.php'; ?>

    <div class="orders-container">
        <a href="index.php" class="back-btn">⬅</a>

        <h1>📦 My Orders</h1>

        <?php if ($result->num_rows > 0) : ?>
            <div class="orders-grid">
                <?php while ($order = $result->fetch_assoc()) : ?>
                    <div class="order-card">
                        <img src="../uploads/<?php echo htmlspecialchars($order['image']); ?>" alt="<?php echo htmlspecialchars($order['product_name']); ?>">
                        <div class="order-info">
                            <h2><?php echo htmlspecialchars($order['product_name']); ?></h2>
                            <p><strong>Quantity:</strong> <?php echo $order['quantity']; ?></p>
                            <p><strong>Total Price:</strong> $<?php echo number_format($order['total_price'], 2); ?></p>
                            <p><strong>Payment:</strong> <?php echo $order['payment_method']; ?></p>
                            <p><strong>Status:</strong> <?php echo $order['status']; ?></p>
                            <a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn">View Details</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <p class="no-orders">You have no orders yet.</p>
        <?php endif; ?>
    </div>

    <?php include 'user_includes/footer.php'; ?>

</body>
<script>
    function toggleMenu() {
        document.querySelector(".nav-links").classList.toggle("active");
    }
</script>
</html>
