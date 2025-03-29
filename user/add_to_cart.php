<?php
session_start();
include '../config/config.php';

// Mock user ID for testing (replace with actual session user ID in real app)
$user_id = $_SESSION['user_id'] ?? 1;

// Check if product ID is in URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$product_id = $_GET['id'];

// Check if product already exists in cart
$check_sql = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ii", $user_id, $product_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    // Optional: Update quantity instead
    header("Location: product.php?id=$product_id&status=exists");
    exit();
}

// Insert into cart
$insert_sql = "INSERT INTO cart (user_id, product_id) VALUES (?, ?)";
$insert_stmt = $conn->prepare($insert_sql);
$insert_stmt->bind_param("ii", $user_id, $product_id);

if ($insert_stmt->execute()) {
    header("Location: product.php?id=$product_id&status=added");
} else {
    echo "Error adding to cart.";
}
?>
