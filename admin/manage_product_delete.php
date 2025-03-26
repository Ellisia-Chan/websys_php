<?php
session_start();
include '../config/config.php';

// Redirect non-admins
if (!isset($_SESSION["user"]) || $_SESSION["account_type"] != 1) {
    header("Location: ../login.php");
    exit();
}

// Check if product ID is provided
if (!isset($_GET['id'])) {
    header("Location: manage_product.php");
    exit();
}

$id = $_GET['id'];

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Product</title>
    <link rel="stylesheet" href="styles/manage_product_delete_style.css"> <!-- Ensure this file exists -->
</head>
<body>

    <?php include 'admin_navbar.php'; ?>

    <div class="admin-container">
        <h1>Delete Product</h1>
        <p>Are you sure you want to delete <strong><?php echo $product['name']; ?></strong>?</p>

        <form action="manage_product_delete_process.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
            <label>Type the product name to confirm:</label>
            <input type="text" name="confirm_name" placeholder="Product Name" required>
            <button type="submit" name="delete_product" class="delete-btn">Confirm Delete</button>
            <a href="manage_product.php" class="btn back-btn">Cancel</a>
        </form>
    </div>

</body>
</html>
