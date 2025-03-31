<?php
include '../config/config.php';
session_start();

// Ensure user is logged in
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = intval($_POST["product_id"]);
    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $shipping_address = trim($_POST["address"]);
    $payment_method = $_POST["payment_method"];
    $quantity = intval($_POST["quantity"]);
    $price = floatval($_POST["price"]);

    // Validate inputs
    if ($quantity <= 0) {
        $_SESSION["error_message"] = "⚠️ Please enter a valid quantity.";
        header("Location: buy.php?id=$product_id");
        exit();
    }
    if (empty($full_name) || empty($email) || empty($phone) || empty($shipping_address)) {
        $_SESSION["error_message"] = "⚠️ All fields are required.";
        header("Location: buy.php?id=$product_id");
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["error_message"] = "⚠️ Invalid email format.";
        header("Location: buy.php?id=$product_id");
        exit();
    }
    if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
        $_SESSION["error_message"] = "⚠️ Invalid phone number format.";
        header("Location: buy.php?id=$product_id");
        exit();
    }
    if (!in_array($payment_method, ["COD", "GCash", "Credit Card", "Shop Voucher"])) {
        $_SESSION["error_message"] = "⚠️ Invalid payment method.";
        header("Location: buy.php?id=$product_id");
        exit();
    }

    // Calculate total price
    $total_price = $price * $quantity;

    // Insert order into database
    $sql = "INSERT INTO orders (user_id, product_id, full_name, email, phone, quantity, total_price, payment_method, shipping_address, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $status = "Pending";
    $stmt->bind_param("iisssdssss", $_SESSION["user_id"], $product_id, $full_name, $email, $phone, $quantity, $total_price, $payment_method, $shipping_address, $status);

    if ($stmt->execute()) {
        // Delete product from cart (if it exists)
        $sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $_SESSION["user_id"], $product_id);
        $stmt->execute();

        // Store order details in session for confirmation page
        $_SESSION["success_message"] = "Thank you, $full_name! Your order has been placed successfully.";
        $_SESSION["payment_method"] = $payment_method;
        $_SESSION["shipping_address"] = $shipping_address;
        $_SESSION["product_id"] = $product_id;

        header("Location: buy_success.php");
        exit();
    } else {
        $_SESSION["error_message"] = "⚠️ Error processing your order. Please try again.";
        header("Location: buy.php?id=$product_id");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>

