<?php
session_start();
include '../config/config.php';

// Redirect non-admins
if (!isset($_SESSION["user"]) || $_SESSION["account_type"] != 1) {
    header("Location: ../login.php");
    exit();
}

// Fetch all products
$product_query = "SELECT * FROM products ORDER BY id ASC";
$product_result = $conn->query($product_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="styles/manage_product_style.css"> <!-- Ensure this file has the styles -->
</head>
<body>

    <?php include 'admin_navbar.php'; ?>

    <div class="admin-container">
        <a href="index.php" class="back-btn-pink">⬅</a>

        <h1>Manage Products</h1>

        <!-- Display Message Below "Manage Products" -->
        <?php
        if (isset($_SESSION['message']) && $_SESSION['message'] != "") {
            echo "<div class='message-container'>" . $_SESSION['message'] . "</div>";
            $_SESSION['message'] = ""; // Clear message after displaying
        }
        ?>

        <!-- Add Product Button (Centered) -->
        <div class="btn-container">
            <a href="manage_product_add.php" class="btn">Add New Product</a>
        </div>

        <!-- Product Grid -->
        <h2>Product List</h2>
        <div class="product-grid">
            <?php
            if ($product_result->num_rows > 0) {
                while ($row = $product_result->fetch_assoc()) {
                    echo "
                    <div class='product-card'>
                        <img src='../uploads/" . $row['image'] . "' alt='" . $row['name'] . "'>
                        <h3>" . $row['name'] . "</h3>
                        <p>$" . $row['price'] . "</p>
                        <a href='manage_product_edit.php?id=" . $row['id'] . "' class='edit-btn'>Edit</a>
                        <a href='manage_product_delete.php?id=" . $row['id'] . "' class='delete-btn'>Delete</a>
                    </div>";
                }
            } else {
                echo "<p>No products found.</p>";
            }
            ?>
        </div>
    </div>

</body>
</html>
