<?php
session_start();
include '../config/config.php';

// Redirect non-admins
if (!isset($_SESSION["user"]) || $_SESSION["account_type"] != 1) {
    header("Location: ../login.php");
    exit();
}

// Store message in session to prevent breaking the layout
if (!isset($_SESSION['message'])) {
    $_SESSION['message'] = "";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_product"])) {
    $name = trim($_POST["name"]);
    $price = $_POST["price"];
    $description = trim($_POST["description"]);
    $image = $_FILES["image"]["name"];
    $target_dir = "../uploads/";

    // Generate unique image name (timestamp + original name)
    $unique_image_name = time() . "_" . basename($image);
    $target_file = $target_dir . $unique_image_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validate input fields
    if (empty($name) || empty($price) || empty($description) || empty($image)) {
        $_SESSION['message'] = "<div class='error'>⚠️ All fields are required.</div>";
    } elseif (!is_numeric($price) || $price <= 0) {
        $_SESSION['message'] = "<div class='error'>⚠️ Price must be a positive number.</div>";
    } else {
        // Validate image file type
        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_types)) {
            $_SESSION['message'] = "<div class='error'>⚠️ Only JPG, JPEG, PNG, and GIF files are allowed.</div>";
        } else {
            // Move uploaded file with unique name
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Insert product into database with new image name
                $sql = "INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sdss", $name, $price, $description, $unique_image_name);

                if ($stmt->execute()) {
                    $_SESSION['message'] = "<div class='success'>✅ Product added successfully!</div>";
                } else {
                    $_SESSION['message'] = "<div class='error'>⚠️ Error adding product.</div>";
                }
            } else {
                $_SESSION['message'] = "<div class='error'>⚠️ Error uploading image.</div>";
            }
        }
    }
    header("Location: manage_product_add.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="styles/manage_product_add_style.css">
</head>
<body>

    <?php include 'admin_navbar.php'; ?>

    <div class="admin-container">
        <h1>Add New Product</h1>

        <!-- Display Message Below "Add New Product" -->
        <?php
        if (isset($_SESSION['message']) && $_SESSION['message'] != "") {
            echo "<div class='message-container'>" . $_SESSION['message'] . "</div>";
            $_SESSION['message'] = ""; // Clear message after displaying
        }
        ?>

        <div class="form-container">
            <form id="productForm" action="manage_product_add.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="name" placeholder="Product Name" required><br>
                <input type="number" name="price" placeholder="Price" step="0.01" required><br>
                <input type="file" name="image" accept="image/*" required><br>
                <textarea name="description" placeholder="Product Description" required></textarea><br>
                <button type="submit" name="add_product">Add Product</button>
            </form>
            <a href="manage_product.php" class="btn back-btn">Back to Products</a>
        </div>
    </div>

    <!-- Form Reset Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if (!empty($_SESSION['message']) && strpos($_SESSION['message'], '✅') !== false) { ?>
                document.getElementById("productForm").reset();
            <?php } ?>
        });
    </script>

</body>
</html>
