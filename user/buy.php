<?php
include '../config/config.php';
session_start();

// Redirect if no product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$product_id = $_GET['id'];

// Fetch product details
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<p>Product not found.</p>";
    exit();
}

$product = $result->fetch_assoc();

// Handle errors
$error_message = "";
if (isset($_SESSION["error_message"])) {
    $error_message = $_SESSION["error_message"];
    unset($_SESSION["error_message"]); // Clear message after displaying
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Shoe Store</title>
    <link rel="stylesheet" href="user_styles/buy_style.css">
</head>
<body>

    <?php include 'user_includes/navbar.php'; ?>

    <div class="checkout-container">

        <a href="index.php" class="back-btn">⬅</a>
        <h1>Checkout</h1>

        <!-- Error Message Display -->
        <?php if (!empty($error_message)) : ?>
            <div class="message-container error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Product Details -->
        <div class="product-summary">
            <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <div class="product-info">
                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                <p class="total-price">Total: $<span id="total"><?php echo number_format($product['price'], 2); ?></span></p>
            </div>
        </div>

        <!-- Checkout Form -->
        <div class="checkout-form">
            <h2>Shipping Information</h2>
            <form action="process_order.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <input type="hidden" name="price" id="final_price" value="<?php echo $product['price']; ?>">

                <label>Quantity:</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1" required>

                <label>Full Name:</label>
                <input type="text" name="full_name" required placeholder="Enter your full name">

                <label>Email Address:</label>
                <input type="email" name="email" required placeholder="Enter your email">

                <label>Phone Number:</label>
                <input type="tel" name="phone" required placeholder="Enter your phone number">

                <label>Shipping Address:</label>
                <textarea name="address" required placeholder="Enter your full address"></textarea>

                <label>Payment Method:</label>
                <select name="payment_method" required>
                    <option value="COD">Cash on Delivery (COD)</option>
                    <option value="GCash">GCash</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Shop Voucher">Shop Voucher</option>
                </select>

                <button type="submit" class="checkout-btn">Place Order</button>
            </form>
        </div>
    </div>

    <?php include 'user_includes/footer.php'; ?>

    <!-- Quantity & Price Update Script -->
    <script>
        document.getElementById('quantity').addEventListener('input', function() {
            let price = <?php echo $product['price']; ?>;
            let quantity = this.value;
            let total = price * quantity;
            document.getElementById('total').innerText = total.toFixed(2);
            document.getElementById('final_price').value = total;
        });
    </script>

</body>
<script>
    function toggleMenu() {
        document.querySelector(".nav-links").classList.toggle("active");
    }
</script>
</html>
