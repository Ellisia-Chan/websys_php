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
    <title>Edit Product</title>
    <link rel="stylesheet" href="styles/manage_product_edit_style.css"> 
</head>
<body>

    <?php include 'admin_navbar.php'; ?>

    <div class="admin-container">
        <h1>Edit Product</h1>

        <!-- Display Success/Error Message -->
        <?php
        if (isset($_SESSION['message']) && $_SESSION['message'] != "") {
            echo "<div class='message-container'>" . $_SESSION['message'] . "</div>";
            $_SESSION['message'] = ""; // Clear message after displaying
        }
        ?>

        <div class="form-container">
            <form action="manage_product_edit_process.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

                <div class="form-group">
                    <label>Product Name:</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Price ($):</label>
                    <input type="number" name="price" value="<?php echo $product['price']; ?>" step="0.01" required>
                </div>

                <div class="form-group">
                    <label>Current Image:</label><br>
                    <img src="../uploads/<?php echo $product['image']; ?>" class="product-preview" alt="Product Image">
                </div>

                <div class="form-group">
                    <label>Change Image (Optional):</label>
                    <input type="file" name="image" accept="image/*">
                </div>

                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>

                
                <div class="button-container">
                    <button type="submit" name="update_product" class="btn primary-btn">Save</button>
                    <a href="manage_product.php" class="btn secondary-btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
