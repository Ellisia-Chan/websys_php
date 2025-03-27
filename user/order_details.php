<?php
session_start();
include '../config/config.php';

// Ensure user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Ensure order ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = $_GET['id'];

// Fetch order details
$sql = "SELECT orders.id, orders.quantity, orders.total_price, orders.payment_method, 
               orders.shipping_address, orders.full_name, orders.email, orders.phone, orders.order_date, 
               products.name AS product_name, products.image 
        FROM orders 
        JOIN products ON orders.product_id = products.id 
        WHERE orders.id = ? AND orders.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<p>Order not found.</p>";
    exit();
}

$order = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Shoe Store</title>
    <link rel="stylesheet" href="user_styles/order_details_style.css"> <!-- Separate CSS file -->
</head>
<body>

    <?php include 'user_includes/navbar.php'; ?>

    <div class="order-details-container">
        <h1>📦 Order Details</h1>

        <div class="order-summary">
            <img src="../uploads/<?php echo htmlspecialchars($order['image']); ?>" alt="<?php echo htmlspecialchars($order['product_name']); ?>">
            <div class="order-info">
                <h2><?php echo htmlspecialchars($order['product_name']); ?></h2>
                <p><strong>Order Number:</strong> #<?php echo $order['id']; ?></p>
                <p><strong>Ordered On:</strong> <?php echo date("F j, Y", strtotime($order['order_date'])); ?></p>
                <p><strong>Quantity:</strong> <?php echo $order['quantity']; ?></p>
                <p><strong>Total Price:</strong> $<?php echo number_format($order['total_price'], 2); ?></p>
                <p><strong>Payment Method:</strong> <?php echo $order['payment_method']; ?></p>
            </div>
        </div>

        <div class="shipping-details">
            <h2>🚚 Shipping Information</h2>
            <p><strong>Full Name:</strong> <?php echo htmlspecialchars($order['full_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($order['shipping_address']); ?></p>

            <div style="text-align: right; margin-top: 20px;">
                <a href="order_details_confirm_delete.php?id=<?php echo $order['id']; ?>" class="btn delete-btn">🗑️ Delete Order</a>
            </div>


        </div>

        <div class="button-group">
            <a href="orders.php" class="btn">📋 View All Orders</a>
            <a href="index.php" class="btn back-btn">🏠 Back to Home</a>
        </div>
    </div>

    <?php include 'user_includes/footer.php'; ?>

</body>
<script>
    function toggleMenu() {
        document.querySelector(".nav-links").classList.toggle("active");
    }
</script>
</html>
