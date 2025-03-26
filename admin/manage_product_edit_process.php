<?php
session_start();
include '../config/config.php';

// Redirect non-admins
if (!isset($_SESSION["user"]) || $_SESSION["account_type"] != 1) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_product"])) {
    $id = $_POST["id"];
    $name = trim($_POST["name"]);
    $price = $_POST["price"];
    $description = trim($_POST["description"]);
    $new_image = $_FILES["image"]["name"];
    $target_dir = "../uploads/";

    // Fetch old image name before updating
    $query = "SELECT image FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        $_SESSION['message'] = "<div class='error'>⚠️ Product not found.</div>";
        header("Location: manage_product.php");
        exit();
    }

    // If a new image is uploaded, process it
    if (!empty($new_image)) {
        $unique_image_name = time() . "_" . basename($new_image);
        $target_file = $target_dir . $unique_image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate image type
        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_types)) {
            $_SESSION['message'] = "<div class='error'>⚠️ Only JPG, JPEG, PNG, and GIF files are allowed.</div>";
            header("Location: manage_product_edit.php?id=$id");
            exit();
        }

        // Move uploaded file
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $_SESSION['message'] = "<div class='error'>⚠️ Error uploading image.</div>";
            header("Location: manage_product_edit.php?id=$id");
            exit();
        }

        // Delete the old image if a new one is uploaded
        $old_image_path = "../uploads/" . $product['image'];
        if (file_exists($old_image_path)) {
            unlink($old_image_path);
        }
    } else {
        $unique_image_name = $product['image']; // Keep the old image
    }

    // Update product details
    $update_query = "UPDATE products SET name = ?, price = ?, description = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sdssi", $name, $price, $description, $unique_image_name, $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "<div class='success'>✅ Product updated successfully!</div>";
    } else {
        $_SESSION['message'] = "<div class='error'>⚠️ Error updating product.</div>";
    }

    header("Location: manage_product.php");
    exit();
}
?>
