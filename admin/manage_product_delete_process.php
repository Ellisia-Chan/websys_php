<?php
session_start();
include '../config/config.php';

// Redirect non-admins
if (!isset($_SESSION["user"]) || $_SESSION["account_type"] != 1) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_product"])) {
    $id = $_POST["id"];
    $confirm_name = trim($_POST["confirm_name"]);

    // Fetch product details
    $product_query = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($product_query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        $_SESSION['message'] = "<div class='error'>⚠️ Product not found.</div>";
        header("Location: manage_product.php");
        exit();
    }

    // Check if the entered name matches the actual product name
    if ($confirm_name !== $product['name']) {
        $_SESSION['message'] = "<div class='error'>⚠️ Product name does not match.</div>";
        header("Location: manage_product_delete.php?id=$id");
        exit();
    }

    // Delete the product image from the folder
    $image_path = "../uploads/" . $product['image'];
    if (file_exists($image_path)) {
        unlink($image_path);
    }

    // Delete related orders from the database
    $delete_orders_query = "DELETE FROM orders WHERE product_id = ?";
    $stmt = $conn->prepare($delete_orders_query);
    $stmt->bind_param("i", $id);
    $stmt->execute();


    // Delete the product from the database
    $delete_query = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "<div class='success'>✅ Product deleted successfully!</div>";
    } else {
        $_SESSION['message'] = "<div class='error'>⚠️ Error deleting product.</div>";
    }

    header("Location: manage_product.php");
    exit();
}
?>
