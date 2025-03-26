<?php
session_start();
include '../config/config.php';

// Redirect if no order success message
if (!isset($_SESSION["success_message"]) || !isset($_SESSION["product_id"])) {
    header("Location: index.php");
    exit();
}

$success_message = $_SESSION["success_message"];
$product_id = $_SESSION["product_id"];
$payment_method = $_SESSION["payment_method"];
$shipping_address = $_SESSION["shipping_address"];

// Unset session variables after displaying the message
unset($_SESSION["success_message"], $_SESSION["product_id"], $_SESSION["payment_method"], $_SESSION["shipping_address"]);

// Fetch product image from the database using the product ID
$sql = "SELECT image FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// Default image if product not found
$product_image = ($product) ? "../uploads/" . $product["image"] : "../uploads/shoe_placeholder.jpg";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Successful - Shoe Store</title>
    <link rel="stylesheet" href="user_styles/buy_success.css">
</head>
<body>

    <?php include 'user_includes/navbar.php'; ?>

    <div class="success-container">
        <h1>🎉 Order Placed Successfully!</h1>
        <p><?php echo $success_message; ?></p>

        <div class="order-summary">
            <img src="<?php echo htmlspecialchars($product_image); ?>" alt="Ordered Product">
            <div class="order-info">
                <h2>Order Details</h2>
                <p><strong>Order Number:</strong> #<?php echo rand(100000, 999999); ?></p>
                <p><strong>Estimated Delivery:</strong> 3-5 Business Days</p>
                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($payment_method); ?></p>
                <p><strong>Shipping Address:</strong> <?php echo htmlspecialchars($shipping_address); ?></p>
            </div>
        </div>

        <div class="button-group">
            <a href="orders.php" class="btn">📦 View Order History</a>
            <a href="index.php" class="btn back-btn">🏠 Back to Home</a>
        </div>
    </div>

    <?php include 'user_includes/footer.php'; ?>

</body>
</html>
